<?php

namespace FS\BannedWordHighlighter\XF\Pub\Controller;

class ApprovalQueue extends XFCP_ApprovalQueue
{
    public function actionIndex()
    {
        $approvalQueueRepo = $this->getApprovalQueueRepo();

        $unapprovedFinder = $approvalQueueRepo->findUnapprovedContent();

        $filters = $this->getQueueFilterInput();
        $this->applyQueueFilters($unapprovedFinder, $filters);

        /** @var \XF\Entity\ApprovalQueue[]|\XF\Mvc\Entity\ArrayCollection $unapprovedItems */

        $unapprovedItems = $unapprovedFinder->fetch();

        if ($unapprovedItems->count() != $this->app->unapprovedCounts['total']) {
            $approvalQueueRepo->rebuildUnapprovedCounts();
        }

        $approvalQueueRepo->addContentToUnapprovedItems($unapprovedItems);
        $approvalQueueRepo->cleanUpInvalidRecords($unapprovedItems);
        $unapprovedItems = $approvalQueueRepo->filterViewableUnapprovedItems($unapprovedItems);

        foreach ($unapprovedItems as $unapprovedItem) {

            if ($unapprovedItem->Content instanceof \XF\Entity\Post) {

                $unapprovedItem->Content->message = $this->wordHighlight($unapprovedItem->Content->message);
            } elseif ($unapprovedItem->Content instanceof \XF\Entity\Thread) {

                $unapprovedItem->Content->FirstPost->message = $this->wordHighlight($unapprovedItem->Content->FirstPost->message);
            }
        }

        $viewParams = [
            'filters' => $filters,
            'unapprovedItems' => $unapprovedItems->slice(0, 50),
        ];
        return $this->view('XF:ApprovalQueue\Listing', 'approval_queue', $viewParams);
    }

    protected function wordHighlight($message)
    {
        $options = \XF::options();
        $applicable_words = $options->spamPhrases['phrases'];

        $wordsArray = str_word_count($applicable_words, 1);

        foreach ($wordsArray as $value) {
            $message = str_ireplace($value, '[fs_word_highlight]' . $value . '[/fs_word_highlight]', $message);
        }
        return $message;
    }
}
