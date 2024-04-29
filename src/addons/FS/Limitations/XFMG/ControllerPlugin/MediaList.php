<?php

namespace FS\Limitations\XFMG\ControllerPlugin;

class MediaList extends XFCP_MediaList
{
    public function getMediaListDataFsLimitations(array $sourceCategoryIds, $page = 1, \XF\Entity\User $user = null)
    {
        $mediaRepo = $this->getMediaRepo();

        $allowOwnPending = is_callable([$this->controller, 'hasContentPendingApproval'])
            ? $this->controller->hasContentPendingApproval()
            : true;

        if ($user) {
            $mediaFinder = $mediaRepo->findMediaForUser($user, $sourceCategoryIds, [
                'allowOwnPending' => $allowOwnPending
            ]);
        } else {
            $mediaFinder = $mediaRepo->findMediaForIndex($sourceCategoryIds, [
                'allowOwnPending' => $allowOwnPending
            ]);
        }

        $filters = $this->getFilterInput();
        $this->applyFilters($mediaFinder, $filters);
        $isDateLimited = (!$user && $this->options()->xfmgMediaIndexLimit && empty($filters['no_date_limit']));

        if ($isDateLimited) {
            $mediaFinder->limitByDate($this->options()->xfmgMediaIndexLimit);
        }

        $totalItems = $mediaFinder->total();

        $page = $this->filterPage($page);
        $perPage = $this->options()->fs_limitations_xfmgMediaPerPage;

        if ($this->responseType() == 'rss') {
            $page = 1;
            $perPage *= 2;
            // we generally want a larger number of items and only those from page 1
        }

        $mediaFinder->limitByPage($page, $perPage);
        $mediaItems = $mediaFinder->fetch()->filterViewable();

        if (!empty($filters['owner_id']) && !$user) {
            $ownerFilter = $this->em()->find('XF:User', $filters['owner_id']);
        } else {
            $ownerFilter = null;
        }

        $canInlineMod = ($user && \XF::visitor()->user_id == $user->user_id);
        if (!$canInlineMod) {
            foreach ($mediaItems as $mediaItem) {
                /** @var \XFMG\Entity\MediaItem $mediaItem */
                if ($mediaItem->canUseInlineModeration()) {
                    $canInlineMod = true;
                    break;
                }
            }
        }

        $mediaEndOffset = ($page - 1) * $perPage + count($mediaItems);
        $showDateLimitDisabler = ($isDateLimited && $mediaEndOffset >= $totalItems);

        return [
            'mediaItems' => $mediaItems,
            'filters' => $filters,
            'ownerFilter' => $ownerFilter,
            'canInlineMod' => $canInlineMod,
            'showDateLimitDisabler' => $showDateLimitDisabler,

            'totalItems' => $totalItems,
            'page' => $page,
            'perPage' => $perPage,

            'user' => $user
        ] + $this->getMediaListMessages();
    }
}
