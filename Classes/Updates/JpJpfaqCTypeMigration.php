<?php

declare(strict_types=1);

namespace Jp\Jpfaq\Updates;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[UpgradeWizard('jpJpfaqCTypeMigration')]
final class JpJpfaqCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    private const CTYPE = 'jpfaq_faq';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
        private readonly FlexFormTools $flexFormTools,
    ) {
        parent::__construct($connectionPool);
    }

    public function getTitle(): string
    {
        return 'Migrate "jpFAQ" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "jpFAQ" plugin is registered as content element. Update migrates existing records and backend user permissions, '
            . 'and moves the "Record Storage Page" of plugins from before TYPO3 v13 to the "Startingpoint" in the plugin options.';
    }

    public function updateNecessary(): bool
    {
        return parent::updateNecessary() || $this->getStartingPointsToMigrate() !== [];
    }

    public function executeUpdate(): bool
    {
        parent::executeUpdate();

        $connection = $this->connectionPool->getConnectionForTable(self::TABLE_CONTENT);
        foreach ($this->getStartingPointsToMigrate() as $uid => $flexForm) {
            $connection->update(
                self::TABLE_CONTENT,
                ['pi_flexform' => $this->flexFormTools->flexArray2Xml($flexForm)],
                ['uid' => $uid]
            );
        }

        return true;
    }

    /**
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'jpfaq_faq' => self::CTYPE,
        ];
    }

    /**
     * The "pages" field is no longer shown in the plugin form and the questions are only
     * selected by the startingpoint, so without this the plugin shows the questions of all pages.
     *
     * @return array<int, array<string, mixed>> FlexForm data with the startingpoint set, by content element uid
     */
    private function getStartingPointsToMigrate(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_CONTENT);
        $queryBuilder->getRestrictions()->removeAll();
        $records = $queryBuilder
            ->select('uid', 'pages', 'pi_flexform')
            ->from(self::TABLE_CONTENT)
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter(self::CTYPE)),
                $queryBuilder->expr()->isNotNull('pages'),
                $queryBuilder->expr()->neq('pages', $queryBuilder->createNamedParameter(''))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        $flexForms = [];
        foreach ($records as $record) {
            $flexForm = GeneralUtility::xml2array((string)$record['pi_flexform']);
            if (!is_array($flexForm)) {
                $flexForm = [];
            }
            if ((string)($flexForm['data']['main']['lDEF']['settings.startingpoint']['vDEF'] ?? '') !== '') {
                continue;
            }
            $flexForm['data']['main']['lDEF']['settings.startingpoint']['vDEF'] = (string)$record['pages'];
            $flexForms[(int)$record['uid']] = $flexForm;
        }

        return $flexForms;
    }
}
