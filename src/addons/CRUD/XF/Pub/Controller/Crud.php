<?php

namespace CRUD\XF\Pub\Controller;

// require __DIR__ . '/../../../vendor/autoload.php';

// // use FFMpeg\FFMpeg;
// // use FFMpeg\Coordinate\TimeCode;

// require 'vendor/autoload.php';

// use FFMpeg\FFMpeg;
// use FFMpeg\Coordinate\TimeCode;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;


class Crud extends AbstractController
{

    public function actionAbout(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);

        /** @var \XF\Repository\UserFollow $userFollowRepo */
        $userFollowRepo = $this->repository('XF:UserFollow');

        $following = [];
        $followingCount = 0;
        if ($user->Profile->following) {
            $userFollowingFinder = $userFollowRepo->findFollowingForProfile($user);
            $userFollowingFinder->order($userFollowingFinder->expression('RAND()'));

            $following = $userFollowingFinder->fetch(12)->pluckNamed('FollowUser');
            $followingCount = $userFollowingFinder->total();
        }

        $userFollowersFinder = $userFollowRepo->findFollowersForProfile($user);
        $userFollowersFinder->order($userFollowersFinder->expression('RAND()'));

        $followers = $userFollowersFinder->fetch(12)->pluckNamed('User');
        $followersCount = $userFollowersFinder->total();

        if ($this->options()->enableTrophies) {
            /** @var \XF\Repository\Trophy $trophyRepo */
            $trophyRepo = $this->repository('XF:Trophy');
            $trophies = $trophyRepo->findUserTrophies($user->user_id)
                ->with('Trophy')
                ->fetch();
        } else {
            $trophies = null;
        }

        /** @var \XF\Entity\User $user */
        $user = $this->assertRecordExists('XF:User', $params->user_id);

        /** @var \XFMG\ControllerPlugin\MediaList $mediaListPlugin */
        $mediaListPlugin = $this->plugin('XFMG:MediaList');

        $categoryParams = $mediaListPlugin->getCategoryListData();
        $viewableCategoryIds = $categoryParams['viewableCategories']->keys();

        $listParams = $mediaListPlugin->getMediaListData($viewableCategoryIds, $params->page, $user);

        $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['totalItems'], 'media/users', $user);
        $this->assertCanonicalUrl($this->buildLink('media/users', $user, ['page' => $listParams['page']]));

        $viewParams = [
            'user' => $user,

            'following' => $following,
            'followingCount' => $followingCount,
            'followers' => $followers,
            'followersCount' => $followersCount,

            'trophies' => $trophies
        ] + $categoryParams + $listParams;
        return $this->view('XF:Member\About', 'member_about', $viewParams);
    }

    // public function generateThumbnail()
    // {
    //     try {
    //         // Initialize FFMpeg
    //         $ffmpeg = FFMpeg::create();

    //         // Open the input video file
    //         $video = $ffmpeg->open('video.mp4');

    //         // Capture a frame at the specified time (default: 3 seconds) and save it as a thumbnail
    //         $frame = $video->frame(TimeCode::fromSeconds(2));
    //         $frame->save('thumbnail.jpg');

    //         return true; // Thumbnail capture successful
    //     } catch (\Exception $e) {
    //         // Handle any exceptions, e.g., log the error
    //         error_log('Error capturing thumbnail: ' . $e->getMessage());
    //         return false; // Thumbnail capture failed
    //     }
    // }

    // public function actionIndex(ParameterBag $params)
    // {

    //     $thumb = $this->generateThumbnail();

    //     var_dump($thumb);
    //     exit;

    //     // // Initialize FFMpeg
    //     // $ffmpeg = FFMpeg::create();

    //     // // Open the input video file
    //     // $video = $ffmpeg->open('video.mp4');

    //     // // Capture a frame at 2 seconds and save it as a thumbnail
    //     // $frame = $video->frame(TimeCode::fromSeconds(3));
    //     // $frame->save('thumbnail.jpg');

    //     // exit;


    //     // $sec = 10;
    //     // // $movie = 'test.mp4';
    //     // $thumbnail = 'thumbnail.png';
    //     // $movie = 'data://video/0/330-6ad3a94ff07e7210031a24eeb1f11849.mp4';

    //     // $ffmpeg = FFMpeg\FFMpeg::create();
    //     // $video = $ffmpeg->open($movie);
    //     // $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
    //     // $frame->save($thumbnail);
    //     // echo '<img src="' . $thumbnail . '">';

    //     // exit;

    //     // $frame = 1;
    //     // $movie = 'data://video/0/330-6ad3a94ff07e7210031a24eeb1f11849.mp4';
    //     // $thumbnail = 'thumbnail.png';

    //     // $mov = new ffmpeg_movie($movie);
    //     // $frame = $mov->getFrame($frame);

    //     // var_dump($frame);
    //     // exit;
    //     // if ($frame) {
    //     //     $gd_image = $frame->toGDImage();
    //     //     if ($gd_image) {
    //     //         imagepng($gd_image, $thumbnail);
    //     //         imagedestroy($gd_image);
    //     //         echo '<img src="' . $thumbnail . '">';
    //     //     }
    //     // }


    //     $db = $this->app->db();
    //     $em = $this->app->em();
    //     $imageManager = $this->app->imageManager();

    //     /** @var \XF\Entity\AttachmentData $attachData */
    //     $attachData = $em->find('XF:AttachmentData', 331);
    //     // $attachData = $em->find('XF:AttachmentData', 330);
    //     $abstractedPath = $attachData->getAbstractedDataPath();

    //     // if (
    //     //     $attachData && $attachData->width && $attachData->height
    //     //     && $imageManager->canResize($attachData->width, $attachData->height)
    //     //     && $this->app->fs()->has($abstractedPath)
    //     // ) {
    //     $tempFile = \XF\Util\File::copyAbstractedPathToTempFile($abstractedPath);

    //     // var_dump($tempFile);
    //     // exit;

    //     // temp files are automatically cleaned up at the end of the request

    //     /** @var \XF\Service\Attachment\Preparer $insertService */
    //     $insertService = \XF::app()->service('XF:Attachment\Preparer');

    //     $tempThumb = $insertService->generateAttachmentThumbnail($tempFile, $thumbWidth, $thumbHeight);

    //     // var_dump($tempThumb);
    //     // exit;

    //     // echo "<pre>";
    //     // var_dump($tempThumb);
    //     // exit;

    //     // if ($tempThumb) {
    //     $db->beginTransaction();

    //     // $attachData->thumbnail_width = $thumbWidth;
    //     // $attachData->thumbnail_height = $thumbHeight;
    //     // $attachData->save(true, false);

    //     $thumbPath = $attachData->getAbstractedThumbnailPath();

    //     // echo "<pre>";
    //     // var_dump($thumbPath);
    //     // exit;

    //     try {
    //         \XF\Util\File::copyFileToAbstractedPath($tempThumb, $thumbPath);
    //         $db->commit();
    //     } catch (\Exception $e) {
    //         $db->rollback();
    //         $this->app->logException($e, false, "Thumb rebuild for #: ");
    //     }
    //     // }
    //     // }

    //     var_dump("Temp Thumb : " . $tempThumb . "\nTemp Path : " . $thumbPath);
    //     exit;
    // }

    // Fatch all records from xf_crud database

    // http://localhost/xenforo/index.php?crud/

    protected function CheckRequestError($statusCode)
    {
        // if ($statusCode == 404) {
        //     throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_not_found'));
        // } elseif ($statusCode == 401) {
        //     throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_unauthorized'));
        // } elseif ($statusCode == 415) {
        //     throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_unsported_media'));
        // } elseif ($statusCode == 400) {
        //     throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_empty_body'));
        // } elseif ($statusCode == 405) {
        //     throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_method_not_allowed'));
        // } elseif ($statusCode == 500) {
        //     throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_server_error1'));
        // }
    }

    protected function convertMinutes($minutes)
    {

        // Convert minutes to hours
        $hours = floor($minutes / 60);
        // $remaining_minutes = $minutes % 60;

        // Convert hours to days
        $days = floor($hours / 24);
        // $remaining_hours = $hours % 24;

        // Convert days to months
        // Assuming 30 days per month (can be adjusted)
        $months = floor($days / 30);
        // $remaining_days = $days % 30;

        $viewParams = [
            'hours' => $hours,
            'days' => $days,
            'months' => $months,
        ];

        return $viewParams;
    }


    // public function actionIndex(ParameterBag $params)
    // {

    //     $visitor = \XF::visitor();

    //     $accessToken = [
    //         'name' => "Access Token",
    //     ];

    //     // try {
    //     //     $accessToken = $accessToken->getAccessToken();
    //     // } catch (\Exception $e) {
    //     //     throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
    //     // }

    //     $dummyId = time() - rand(1000, 9999);

    //     $finalId = $dummyId;


    //     var_dump($finalId, $dummyId);
    //     exit;

    //     echo "<pre>";
    //     var_dump($visitor);
    //     exit;

    //     // return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', []);
    //     // exit;

    //     // $authUrl = 'https://trakt.tv/oauth/authorize';
    //     // $clientId = '678ef863baa3eca3bfa427eabfa2e353a1bc473bc5355181d85acc3bced5f6ff';
    //     // $redirectUri = 'http://localhost/xenforo/index.php?crud';

    //     // $authParams = [
    //     //     'response_type' => 'code',
    //     //     'client_id' => $clientId,
    //     //     'redirect_uri' => $redirectUri,
    //     // ];

    //     // $curl = curl_init();
    //     // curl_setopt($curl, CURLOPT_URL, $authUrl . '?' . http_build_query($authParams));
    //     // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //     // $response = curl_exec($curl);

    //     // if ($response === false) {
    //     //     echo "cURL Error: " . curl_error($curl) . "\n";
    //     // } else {
    //     //     echo "Visit the following URL to authorize your application:\n" . $response . "\n";
    //     // }

    //     // curl_close($curl);


    //     $endpoint = 'https://api.trakt.tv/users/sean/stats';

    //     $headers = array(
    //         'Content-Type: application/json',
    //         'trakt-api-version: 2',
    //         'trakt-api-key: 1d0f918e4f03cf101d342025c836ad72cb26b24184f6e19d5d499de7710019c2'
    //     );

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, $endpoint);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //     $result = curl_exec($ch);

    //     if ($result === false) {
    //         echo "cURL Error: " . curl_error($ch) . "\n";
    //         exit;
    //     }

    //     curl_close($ch);

    //     $toArray = json_decode($result, true);

    //     $stats = [
    //         'moviesWatched' => $toArray["movies"]["watched"],
    //         'moviesTime' => $this->convertMinutes($toArray["movies"]["minutes"]),
    //         'episodesWatched' => $toArray["episodes"]["watched"],
    //         'episodesTime' => $this->convertMinutes($toArray["episodes"]["minutes"]),
    //     ];


    //     $viewParams = [
    //         'stats' => $stats,
    //     ];



    //     // echo "<pre>";
    //     // var_dump($viewParams);





    //     return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', $viewParams);





    //     exit;









    //     // $this->installedAddOns = \XF::em()->getFinder('XF:AddOn')->fetch()->toArray();

    //     // $array1 = [1, 2, 3];
    //     // $array2 = [4, 5, 6];

    //     // $combined_array = array_merge($array1, $array2);


    //     // echo "<pre>";
    //     // var_dump($combined_array);

    //     // exit;

    //     $conditions = [
    //         ['discussion_type', 'snog_movies_movie'],
    //         ['discussion_type', 'trakt_movies_movie'],
    //     ];

    //     $threadIds = $this->finder('XF:Thread')
    //         ->whereOr($conditions)->where('watch_list', 1)->pluckfrom('thread_id')->fetch()->toArray();

    //     $tmdbMovies = $this->finder('Snog\Movies:Movie')->where('thread_id', $threadIds)->fetch()->toArray();
    //     $traktMovies = $this->finder('nick97\TraktMovies:Movie')->where('thread_id', $threadIds)->fetch()->toArray();

    //     $movies = array_merge($tmdbMovies, $traktMovies);

    //     echo "<pre>";
    //     var_dump($movies);

    //     exit;

    //     $addOns = \XF::app()->addOnManager()->getAllAddOns();
    //     foreach ($addOns as $addOnId => $addOn) {

    //         var_dump($addOnId);
    //         exit;

    //         if (!$addOn->isInstalled()) {
    //         }
    //     }

    //     return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', []);


    //     // $movieFinder = $this->app->finder('Snog\Movies:Movie')
    //     //     ->with('Thread', true)
    //     //     ->where('Thread.discussion_state', '=', 'visible');

    //     // if (!empty($options['node_ids']) && !in_array(0, $options['node_ids'])) {
    //     //     $movieFinder->where('Thread.node_id', $options['node_ids']);
    //     // }


    //     // $movieFinder->order('Thread.last_post_date', 'DESC');

    //     // $movies = $movieFinder->limit(12)->fetch();

    //     // $viewParams = [
    //     //     'movies' => $movies,
    //     //     'sliderOptions' => [
    //     //         'items' => 2,
    //     //         'auto' => false,
    //     //         'pause' => 4000,
    //     //         'controls' => false,

    //     //         'pauseOnHover' => false,
    //     //         'loop' => false,
    //     //         'pager' => false,
    //     //         'itemsWide' => 4,
    //     //         'breakpointWide' => 900,
    //     //         'itemsMedium' => 2,
    //     //         'breakpointMedium' => 480,
    //     //     ],
    //     //     'title' => "Title Only",
    //     // ];

    //     // return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', $viewParams);

    //     // return $this->renderer('widget_snog_movies_poster_slider', $viewParams);





    //     // Define an array of image URLs
    //     $imageUrls = [
    //         "https://images.unsplash.com/photo-1641353989082-9b15fa661805?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODcyOA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1642190672487-22bde32965f7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODcyOA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1641841344411-49dbd02896f4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODcyOA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1643223723262-7ce785730cf6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODcyOA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1640938776314-4d303f8a1380?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc2Mw&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1641259041823-e09935369105?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc2Mw&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1642543492481-44e81e3914a7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc2Mw&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1641118961077-440391095cdc?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc2Mw&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1640767014413-b7d27c58b058?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc5NQ&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1640948612546-3b9e29c23e98?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc5NQ&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1642484865851-111e68695d71?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc5NQ&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1642177584449-fa0b017dccc7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODc5NQ&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1643249960396-d39d2a63ce8a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODg0Mw&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1641424222187-1c336d21804c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODg0OA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1640998483268-d1faffa789ad?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODkwNA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1642034451735-2a8df1eaa2c0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODg4OQ&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1640808238224-5520de93c939?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODg4OQ&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1643039952431-38adfa91f320?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODg0OA&ixlib=rb-1.2.1&q=80&w=400",
    //         "https://images.unsplash.com/photo-1643148636637-58b3eb95cdad?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNDU4OXwwfDF8cmFuZG9tfHx8fHx8fHx8MTY0MzM5ODg0OA&ixlib=rb-1.2.1&q=80&w=400"

    //         // Add more image URLs as needed
    //     ];

    //     // Number of images per group
    //     $imagesPerGroup = 5;

    //     // Calculate the number of groups
    //     $numGroups = ceil(count($imageUrls) / $imagesPerGroup);

    //     // $viewParams = [
    //     //     'imageUrls' => $imageUrls,

    //     //     'imagesPerGroup' => $imagesPerGroup,
    //     //     'numGroups' => intval($numGroups),
    //     // ];

    //     // return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', $viewParams);

    //     // Output the HTML structure
    //     echo '<h2 class="section-title">Trending now</h2>';
    //     echo '<div class="media-container">';
    //     echo '<div class="media-scroller">';

    //     // Loop through each group
    //     for ($group = 1; $group <= $numGroups; $group++) {
    //         echo '<div class="media-group" id="' . $group . '">';

    //         // Loop through each image in the group
    //         for ($i = ($group - 1) * $imagesPerGroup; $i < $group * $imagesPerGroup && $i < count($imageUrls); $i++) {
    //             echo '<div class="media-element">';
    //             echo '<img src="' . $imageUrls[$i] . '" alt="">';
    //             echo '</div>';
    //         }

    //         // Output the "next" link
    //         $nextGroup = ($group % $numGroups) + 1;
    //         echo '<a class="next" href="#' . $nextGroup . '" aria-label="next">';
    //         echo '<svg><use href="#next"></use></svg>';
    //         echo '</a>';

    //         echo '</div>';
    //     }

    //     // Output navigation indicators
    //     echo '<div class="navigation-indicators">';
    //     for ($i = 0; $i < $numGroups; $i++) {
    //         echo '<div></div>';
    //     }
    //     echo '</div>';

    //     echo '</div>';
    //     echo '</div>';
    //     echo '<svg><symbol id="next" viewBox="0 0 256 512"><path fill="white" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z" /></symbol></svg>';

    //     exit;

    //     $viewParams = [
    //         'imageUrls' => $imageUrls,

    //         'imagesPerGroup' => $imagesPerGroup,
    //         'numGroups' => $numGroups,
    //     ];

    //     return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', $viewParams);


    //     // $url = "https://trakt.tv/movies/the-hunger-games-the-ballad-of-songbirds-snakes-2023";

    //     // $pattern = "/https:\/\/trakt\.tv\/movies\//";
    //     // $cleanUrl = preg_replace($pattern, "", $url);

    //     // // $pattern = '/^[0-9]+$/';
    //     // // $numericValue = 'df;lgkdf123oierut';

    //     // var_dump($cleanUrl);
    //     // exit;



    //     // $curl = curl_init();

    //     // $params = [
    //     //     'response_type' => 'code',
    //     //     'client_id' => '1137c10c70622f8cbb2056b83c94111443fbf06560db4fb099b9a9db5adb16b7',
    //     //     'redirect_uri' => 'http://localhost/xenforo/index.php?crud/',
    //     // ];

    //     // $queryString = http_build_query($params);

    //     // curl_setopt($curl, CURLOPT_URL, "https://trakt.tv/oauth/authorize?" . $queryString);
    //     // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     // curl_setopt($curl, CURLOPT_HTTPHEADER, [
    //     //     'Content-Type: application/json',
    //     // ]);

    //     // $server_output = curl_exec($curl);

    //     // $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    //     // $this->CheckRequestError($resCode);
    //     // exit;


    //     // curl_close($curl);

    //     // $getVideoRes = json_decode($server_output, true);

    //     // echo "<pre>";
    //     // var_dump($getVideoRes);
    //     // exit;

    //     // return $getVideoRes["encodeProgress"] >= 50 ? true : false;

    //     // $filePath = 'e-dewan


    //     // // /data/video/29/29528-5c31d513e48b136a676e2f46e012785c.mp4';
    //     // $filePath = 'e-dewan.ams3.cdn.digitaloceanspaces.com/data/video/29/29528-5c31d513e48b136a676e2f46e012785c.mp4';

    //     // // https://e-dewan.ams3.cdn.digitaloceanspaces.com/data/video/29/29528-5c31d513e48b136a676e2f46e012785c.mp4

    //     // // rmdir($file);
    //     // unlink($filePath);

    //     // exit;

    //     // $adapter = \XF::app()->fileSystem();

    //     // $filePath = 'e-dewan/data/video/29/29528-5c31d513e48b136a676e2f46e012785c.mp4';

    //     // $adapter->delete($filePath);

    //     $finder = $this->finder('CRUD\XF:Crud');

    //     // ager filter search wala set hai to ye code chaley ga or is k ander wala function or code run ho ga
    //     if ($this->filter('search', 'uint')) {
    //         $finder = $this->getCrudSearchFinder();

    //         if (count($finder->getConditions()) == 0) {
    //             return $this->error(\XF::phrase('please_complete_required_field'));
    //         }
    //     }
    //     // nai to ye wala run ho ga code jo is ka defaul hai or sarey record show kerwaye ga
    //     else {
    //         $finder->order('id', 'DESC');
    //     }


    //     $page = $params->page;
    //     $perPage = 3;

    //     $finder->limitByPage($page, $perPage);

    //     $viewParams = [
    //         'data' => $finder->fetch(),

    //         'page' => $page,
    //         'perPage' => $perPage,
    //         'total' => $finder->total(),

    //         // ager filter me koch search kia hai to wo is k zareiye hm input tag me show kerwa sakte hain
    //         'conditions' => $this->filterSearchConditions(),
    //     ];

    //     return $this->view('CRUD\XF:Crud\Index', 'crud_record_all', $viewParams);
    // }

    public function actionRepost(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id, ['Thread.Prefix']);
        if (!$post->canEdit($error)) {
            return $this->noPermission($error);
        }

        $visitor = \XF::visitor();

        $secondary_group_ids = $visitor['secondary_group_ids'];
        $secondary_group_ids[] = $visitor['user_group_id'];

        $finder = $this->finder('FS\Limitations:Limitations')->where('user_group_id', $secondary_group_ids)->order('daily_ads', 'DESC')->fetchOne();

        $upgradeUrl = [
            'upgradeUrl' => $this->buildLink('account-upgrade/')
        ];

        if (!$finder) {
            throw $this->exception($this->notFound(\XF::phrase('fs_limitations_repost_not_permission', $upgradeUrl)));
        }

        $nodeIds = explode(",", $finder['node_ids']);

        if (!in_array($post->Thread->Forum->node_id, $nodeIds)) {
            throw $this->exception($this->notFound(\XF::phrase('fs_limitations_repost_not_permission', $upgradeUrl)));
        }

        if ($visitor['daily_ads'] >= $finder['daily_ads']) {
            throw $this->exception($this->notFound(\XF::phrase('fs_limitations_repost_limit_reached', $upgradeUrl)));
        }

        $thread = $post->Thread;

        if ($this->isPost()) {
        }


        if ($this->filter('_xfWithData', 'bool') && $this->filter('_xfInlineEdit', 'bool')) {


            $this->bumpThreadRepo()->bump($thread);
            $this->bumpThreadRepo()->log($thread->thread_id, $visitor->user_id);

            $increment = $visitor->daily_ads + 1;

            $visitor->fastUpdate('daily_ads', $increment);
        }
    }


    public function actionRedirect()
    {
        $visitor = \XF::visitor();
        $auth = $visitor->Auth->getAuthenticationHandler();
        if (!$auth) {
            return $this->noPermission();
        }

        if ($this->isPost()) {
            $visitor->fastUpdate('email', '');

            return $this->redirect($this->buildLink('account/account-details'));
        }

        $viewpParams = [
            'confirmUrl' => $this->buildLink('account/delete-email', $visitor),
            'contentTitle' => $visitor->email,
        ];

        return $this->view('XF\Account', 'fs_email_delete_confirm', $viewpParams);
    }

    public function actionIndex(ParameterBag $params)
    {

        // return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_pg_1', []);
        return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_pg_2', []);

        // $user = \XF::app()->em()->find('XF:User', 1);
        // // $upgrade = \XF::app()->em()->find('XF:UserUpgrade', 3);

        // $mail = \XF::app()->mailer()->newMail()->setTo($user->email);
        // $mail->setTemplate('fs_bitcoin_integ_thanks_redirect_mail');
        // // $mail->setTemplate('fs_limitations_send_payment_confirm_male', [
        // //     'username' => $user->username,
        // //     'title' => $upgrade->title,
        // //     'price' => $upgrade->cost_amount,
        // // ]);
        // $mail->send();

        return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_pg_2', []);

        // $string = "members/testinguser.63/";

        // $pattern = "/^members\/[a-zA-Z0-9]+?\.\d+\/$/";

        // if (preg_match($pattern, $string)) {
        //     echo "String matches the pattern.";
        // } else {
        //     echo "String does not match the pattern.";
        // }


        // exit;



        $url = \xf::app()->request()->getFullRequestUri();

        $urI = \xf::app()->request()->getRequestUri();

        $parsedUrl = parse_url($url);

        $path = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';


        echo "<pre>";
        var_dump($path);
        exit;

        // $mail = $this->app->mailer()->newMail()->setTo('software0house@gmail.com');
        // $mail->setTemplate('fs_limitations_admirer_mail');
        // // $mail->setTemplate('fs_limitations_companion_mail');
        // $mail->send();

        exit;

        $finder = $this->finder('FS\Limitations:Limitations')->fetch();

        if (count($finder) > 0) {

            $existed = false;

            foreach ($finder as $single) {
                $nodeIds = explode(",", $single['node_ids']);

                if (!in_array($forum->node_id, $nodeIds)) {
                    $existed = $single['user_group_id'];
                }
            }

            if ($existed) {
                if (!in_array($existed, $secondary_group_ids)) {
                    throw $this->exception($this->notFound(\XF::phrase('fs_limitations_daily_ads_not_permission', $upgradeUrl)));
                }
            }
        }


        echo '<pre>';
        var_dump($finder);
        exit;

        return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_pg_2', []);


        $params = [
            'product'    => 1,
            'context'    => "Hello World",
            'linkPrefix' => 786
        ];
        return \XF::app()->templater()->renderTemplate('public:crud_record_testing_pg_2', $params);

        // return \XF::app()->templater()->renderTemplate('admin:payment_profile_' . $this->providerId, $data);

        // $viewParams = [

        //     'customMessage' => isset($tag['children'][0]) ? $tag['children'][0] : 'Default message for media tags'
        // ];

        // return $renderer->getTemplater()->renderTemplate('public:fs_custom_message', $viewParams);

        echo "<pre>";
        var_dump((time() + 3600));
        exit;


        $visitor = \XF::visitor();

        // $conditions = [
        //     ['user_group_id', $value->current_userGroup],
        //     ['secondary_group_ids', 'LIKE', '%' . $value->current_userGroup . '%'],
        // ];

        // $secondary_group_ids = implode(",", $visitor['secondary_group_ids']);
        $secondary_group_ids = $visitor['secondary_group_ids'];
        $secondary_group_ids[] = $visitor['user_group_id'];

        $finder = $this->finder('FS\Limitations:Limitations')->where('user_group_id', $secondary_group_ids)->order('daily_repost', 'DESC')->fetchOne();

        if ($visitor['daily_repost'] >= $finder['daily_repost']) {
            throw $this->exception($this->notFound(\XF::phrase('fs_limitations_repost_not_permission', $upgradeUrl)));
        }

        var_dump($visitor['daily_repost']);
        var_dump($finder['daily_repost']);

        // if ($finder) {
        //     $nodeIds = explode(",", $finder['node_ids']);

        //     if (!in_array($post->Thread->Forum->node_id, $nodeIds)) {
        //         echo "hello world";
        //         exit;
        //     }
        // }



        // $users_names = explode(",", $user_name);

        echo "<pre>";
        // var_dump($nodeIds);
        var_dump($visitor['daily_repost']);
        var_dump($finder['daily_repost']);
        exit;


        // $user = \XF::visitor();
        // // $mailer = $this->app->mailer();

        // $mail = $this->app->mailer()->newMail()->setTo('software0house@gmail.com');
        // $mail->setTemplate('fs_bitcoin_send_approveAccount_mail', [
        //     'user' => $user
        // ]);
        // $mail->send();

        // echo "Sent mail to";
        // exit;

        // $this->app->mailer()->newMail()
        //     ->setTemplate('activity_summary')
        //     // ->setTemplate('activity_summary', [
        //     //     'renderedSections' => $instance->getRenderedSections(),
        //     //     'displayValues' => $instance->getDisplayValues()
        //     // ])
        //     ->setToUser($visitor)
        //     ->send();

        // $mail = $mailer->newMail();
        // $mail->setTo('software0house@gmail.com');
        // $mail->setContent(
        //     \XF::phrase('outbound_email_test_subject', ['board' => $this->options()->boardTitle])->render('raw'),
        //     \XF::phrase('outbound_email_test_body', ['username' => \XF::visitor()->username, 'board' => $this->options()->boardTitle])
        // );



        return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_only', []);

        $finder = $this->finder('CRUD\XF:Crud');

        // ager filter search wala set hai to ye code chaley ga or is k ander wala function or code run ho ga
        if ($this->filter('search', 'uint')) {
            $finder = $this->getCrudSearchFinder();

            if (count($finder->getConditions()) == 0) {
                return $this->error(\XF::phrase('please_complete_required_field'));
            }
        }
        // nai to ye wala run ho ga code jo is ka defaul hai or sarey record show kerwaye ga
        else {
            $finder->order('id', 'DESC');
        }

        $page = $params->page;
        $perPage = 3;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'data' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),

            // ager filter me koch search kia hai to wo is k zareiye hm input tag me show kerwa sakte hain
            'conditions' => $this->filterSearchConditions(),
        ];

        return $this->view('CRUD\XF:Crud\Index', 'crud_record_all', $viewParams);
    }


    public function actionUpload()
    {
        $em = $this->app->em();

        /** @var \XF\Entity\AttachmentData $attachData */
        $attachData = $em->find('XF:AttachmentData', 330);
        $abstractedPath = $attachData->getAbstractedDataPath();

        $abstractedPath = "data://video/0/330-6ad3a94ff07e7210031a24eeb1f11849.mp4";

        $videoFile = $_FILES['bunny_video'];

        $tempFile = $videoFile['tmp_name'];

        $fileDataPath = 'data://CrudTesting/';

        $video = 'data://CrudTesting/Video.mp4';

        // Define the data stream path
        // $videoStreamPath = 'data://CrudTesting/Video.mp4';

        // Read the content of the data stream
        // $videoContents = file_get_contents($videoStreamPath);

        var_dump($videoFile);
        exit;


        $videoExtenion = pathinfo($videoFile['name'], PATHINFO_EXTENSION);

        // $moveVideo = \XF\Util\File::copyFileToAbstractedPath($videoFile['tmp_name'],  $fileDataPath . time() . "." . $videoExtenion);

        $thumbnailPath = $fileDataPath . time() . '_thumbnail.jpg';

        /** @var \XF\Service\Attachment\Preparer $insertService */
        $insertService = \XF::app()->service('XF:Attachment\Preparer');

        $tempThumb = $insertService->generateAttachmentThumbnail($tempFile, $thumbWidth, $thumbHeight);

        var_dump($tempThumb);
        exit;

        $db = $this->app->db();

        if ($tempThumb) {
            $db->beginTransaction();

            // $attachData->thumbnail_width = $thumbWidth;
            // $attachData->thumbnail_height = $thumbHeight;
            // $attachData->save(true, false);

            // $thumbPath = $attachData->getAbstractedThumbnailPath();
            try {
                $thumbIsSave = \XF\Util\File::copyFileToAbstractedPath($tempThumb, $thumbnailPath);
                $db->commit();
            } catch (\Exception $e) {
                $db->rollback();
                $this->app->logException($e, false, "Thumb rebuild for #: ");
            }
        }

        // Generate a video thumbnail using the PHP-FFMpeg library

        var_dump($thumbIsSave);
        exit;


        // Capture the video thumbnail using PHP-FFMpeg (new code)
        // $videoPath = $fileDataPath . basename($moveVideo); // Path to the uploaded video
        // $thumbnailPath = $fileDataPath . time() . '_thumbnail.jpg'; // Path to save the thumbnail

        // if (captureVideoThumbnail($videoPath, $thumbnailPath)) {
        //     // Thumbnail capture successful
        //     echo 'Video and thumbnail uploaded successfully.';
        // } else {
        //     // Thumbnail capture failed
        //     echo 'Error capturing video thumbnail.';
        // }

        // exit;
        // $ffmpegCommand = "ffmpeg -i " . escapeshellarg($videoFile['tmp_name']) . " -ss 00:00:02 -vframes 1 " . escapeshellarg($thumbnailPath);

        // exec($ffmpegCommand);

        // // var_dump(exec($ffmpegCommand));
        // // exit;

        // if (file_exists($thumbnailPath)) {
        //     $videoThumbnail = $thumbnailPath;
        // } else {
        //     $videoThumbnail = null;
        // }

        var_dump("videoThumbnail : " . $videoThumbnail);
        exit;

        $viewParams = [
            'status' => $moveVideo ?  true : false,
            'bunnyVideoId' => $createVideo ? $createVideo['guid'] : ''
        ];

        $this->setResponseType('json');
        $view = $this->view();
        $view->setJsonParam('data', $viewParams);
        return $view;
    }

    public function actionAdd()
    {
        return $this->view('CRUD\XF:Crud\Index', 'crud_record_testing_pg_2', []);

        $crud = $this->em()->create('CRUD\XF:Crud');
        return $this->crudAddEdit($crud);
    }

    public function actionEdit(ParameterBag $params)
    {
        $crud = $this->assertDataExists($params->id);
        return $this->crudAddEdit($crud);
    }

    protected function crudAddEdit(\CRUD\XF\Entity\Crud $crud)
    {
        $viewParams = [
            'crud' => $crud
        ];

        return $this->view('CRUD\XF:Crud\Add', 'crud_record_insert', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->id) {
            $crud = $this->assertDataExists($params->id);
        } else {
            $crud = $this->em()->create('CRUD\XF:Crud');
        }

        $this->crudSaveProcess($crud)->run();

        return $this->redirect($this->buildLink('crud'));
    }

    protected function crudSaveProcess(\CRUD\XF\Entity\Crud $crud)
    {
        $input = $this->filter([
            'name' => 'str',
            'class' => 'str',
            'rollNo' => 'int',
        ]);

        $form = $this->formAction();
        $form->basicEntitySave($crud, $input);

        return $form;
    }

    public function actionDeleteRecord(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('crud/delete-record', $replyExists),
            null,
            $this->buildLink('crud'),
            "{$replyExists->id} - {$replyExists->name}"
        );
    }

    // plugin for check id exists or not 

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \CRUD\XF\Entity\Crud
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('CRUD\XF:Crud', $id, $extraWith, $phraseKey);
    }

    // filter bar k input tag k ander value ko get or set krney k liye ye method call kr rahey hain

    protected function filterSearchConditions()
    {
        return $this->filter([
            'name' => 'str',
            'rClass' => 'str',
            'rollNo' => 'str',
        ]);
    }

    // filter wala form show kerwaney k liye ye use ho ga

    public function actionRefineSearch()
    {

        $viewParams = [
            'conditions' => $this->filterSearchConditions(),
        ];

        return $this->view('CRUD\XF:Crud\RefineSearch', 'crud_record_search_filter', $viewParams);
    }

    // ider hm condition apply kr rahey hain kr filter me koi ho gi to or wapis index waley function me return kr k result ko show kerwa rahey hain

    protected function getCrudSearchFinder()
    {
        $conditions = $this->filterSearchConditions();

        $finder = $this->finder('CRUD\XF:Crud');

        if ($conditions['name'] != '') {
            $finder->where('name', 'LIKE', '%' . $finder->escapeLike($conditions['name']) . '%');
        }

        if ($conditions['rClass'] != '') {
            $finder->where('class', 'LIKE', '%' . $finder->escapeLike($conditions['rClass']) . '%');
        }

        if ($conditions['rollNo'] != '') {
            $finder->where('rollNo', 'LIKE', '%' . $finder->escapeLike($conditions['rollNo']) . '%');
        }

        return $finder;
    }
}
