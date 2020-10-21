<?php

namespace Jp\Jpfaq\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Jp\Jpfaq\Domain\Repository\QuestionRepository;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

/**
 * AjaxFeedbackController
 */
class AjaxFeedbackController
{
    /**
     * questionRepository
     *
     * @var QuestionRepository
     */
    protected $questionRepository;

    /**
     * objectManager
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * frontendAuthentication
     *
     * @var FrontendUserAuthentication
     */
    protected $frontendAuthentication;

    /**
     * configurationManager
     *
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;


    public function __construct()
    {
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
        $this->questionRepository = $this->objectManager->get(QuestionRepository::class);
        $this->frontendAuthentication = $this->objectManager->get(FrontendUserAuthentication::class);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */

    public function processRequest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $request->getQueryParams();
        $helpfulness = $this->updateHelpful($params['question'], $params['helpful']);
        $responseJson = json_encode(['helpfulness' => $helpfulness]);
        $response->getBody()->write($responseJson);
        return $response;
    }

    /**
     * Update helpful or nothelpful of a question in db and session
     *
     * @param string $questionUid
     * @param bool $helpful
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     *
     * @return bool
     */
    private function updateHelpful(string $questionUid, bool $helpful)
    {
        $questionUid = (int)$questionUid;
        $question = $this->questionRepository->findByUid((int)$questionUid);
        $questionHelpful = $question->getHelpful();
        $questionNotHelpful = $question->getNothelpful();
        $userClickedHelpfulness = $this->frontendAuthentication->getKey('ses', 'tx_jpfaq_helpfulness_' . $questionUid);
        $isHelpful = false;

        // User already clicked helpful for this question this session
        if (isset($userClickedHelpfulness)) {
            // User changes helpful to nothelpful
            if ($userClickedHelpfulness & !$helpful) {
                $question->setNothelpful($questionNotHelpful + 1);
                if ($questionHelpful > 0) {
                    $question->setHelpful($questionHelpful - 1);
                }
                $isHelpful = false;
            } elseif (!$userClickedHelpfulness & $helpful) {
                // User changes nothelpful to helpful
                $question->setHelpful($questionHelpful + 1);
                if ($questionNotHelpful > 0) {
                    $question->setNothelpful($questionNotHelpful - 1);
                }
                $isHelpful = true;
            }
        } else {
            // User has not clicked helpful or nothelpful for this question this session
            if ($helpful) {
                $question->setHelpful($questionHelpful + 1);
                $isHelpful = true;
            } else {
                $question->setNothelpful($questionNotHelpful + 1);
                $isHelpful = false;
            }
        }

        $this->questionRepository->update($question);

        // Store user interaction on helpfulness in session
        $this->frontendAuthentication->setKey('ses', 'tx_jpfaq_helpfulness_' . $questionUid, $helpful);
        return $isHelpful;
    }
}