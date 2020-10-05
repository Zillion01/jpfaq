<?php

namespace Jp\Jpfaq\ViewHelpers;

/**
 * This file is part of the "jpFaq" Extension for TYPO3 CMS.
 *
 * Source: ext news
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
use Jp\Jpfaq\Domain\Model\Question;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to exclude question items in other plugins
 *
 * # Example: Basic example
 *
 * <code>
 * <n:excludeAlreadyDisplayedQuestions question="{question}" />
 * </code>
 * <output>
 * None
 * </output>
 *
 */
class ExcludeAlreadyDisplayedQuestionsViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('question', Question::class, 'question item', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $question = $arguments['question'];
        $uid = $question->getUid();

        if (empty($GLOBALS['EXT']['jpfaq']['alreadyDisplayed'])) {
            $GLOBALS['EXT']['jpfaq']['alreadyDisplayed'] = [];
        }
        $GLOBALS['EXT']['jpfaq']['alreadyDisplayed'][$uid] = $uid;

        // Add localized uid as well
        $originalUid = (int)$question->_getProperty('_localizedUid');
        if ($originalUid > 0) {
            $GLOBALS['EXT']['jpfaq']['alreadyDisplayed'][$originalUid] = $originalUid;
        }
    }
}
