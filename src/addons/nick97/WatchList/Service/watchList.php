<?php

namespace nick97\WatchList\Service;

use XF\Mvc\FormAction;

class watchList extends \XF\Service\AbstractService
{
    protected $bunnyLibraryId;

    public function getStatsById($id, $clientKey)
    {
        $endpoint = 'https://api.trakt.tv/users/' . $id . '/stats';
        // $endpoint = 'https://api.trakt.tv/users/sean/stats';

        $headers = array(
            'Content-Type: application/json',
            'trakt-api-version: 2',
            'trakt-api-key: ' . $clientKey
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        $resCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->CheckRequestError($resCode);

        curl_close($ch);

        $toArray = json_decode($result, true);

        return $toArray;
    }

    protected function CheckRequestError($statusCode)
    {
        if ($statusCode == 404) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_not_found'));
        } elseif ($statusCode == 401) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_unauthorized'));
        } elseif ($statusCode == 415) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_unsported_media'));
        } elseif ($statusCode == 400) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_empty_body'));
        } elseif ($statusCode == 405) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_method_not_allowed'));
        } elseif ($statusCode == 500) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_server_error'));
        } elseif ($statusCode != 200) {
            throw new \XF\PrintableException(\XF::phrase('nick9t_watchlist_request_not_success'));
        }
    }
}
