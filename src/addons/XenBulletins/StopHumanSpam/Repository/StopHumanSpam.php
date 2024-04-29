<?php

namespace XenBulletins\StopHumanSpam\Repository;

use XF\Mvc\Entity\Repository;
use XF\PrintableException;

class StopHumanSpam extends Repository {

    public function removeSpecialCharacters($message) {
        $charsToRemove = \XF::options()->AwedoTceSpecialChars;
        $charsToRemove = str_replace(' ', '', $charsToRemove);  // remove all blanks
        // remove all special chars
        foreach (str_split($charsToRemove) as $charToRemove) {
            $message = str_replace($charToRemove, '', $message);
        }

        return $message;
    }

    public function contentHasBannedWords($message, $bannedWords = '') {


        $message = $this->removeSpecialCharacters($message);

        $message = 'ztstart ' . $message . ' ztend';

        if (!$message || !$bannedWords) {
            return false;
        }

        $bWords = array_map('trim', explode(",", $bannedWords));

        foreach ($bWords as $bWord) {
            if ($bWord) {
                if ((substr($bWord, 0, 1) == '*') && (substr($bWord, -1) == '*')) { //if it starts and ends with a *
                    $bWord = str_replace('*', '', $bWord);

                    preg_match("/(.*)" . preg_quote($bWord) . "(.*)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }

                if (substr($bWord, 0, 1) == '*') { //if it starts with a *
                    $bWord = str_replace('*', '', $bWord);

                    preg_match("/(.*)" . preg_quote($bWord) . "(?!\pL)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }

                if (substr($bWord, -1) == '*') { //if it ends with a *
                    $bWord = str_replace('*', '', $bWord);
                    preg_match("/(?<!\pL)" . preg_quote($bWord) . "(.*)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }

                if (substr($bWord, 0, 1) != '*') {
                    preg_match("/(?<!\pL)" . preg_quote($bWord) . "(?!\pL)/i", $message, $matches);
                    if ($matches) {
                        return $bWord;
                    }
                }
            }
        }

        // now we check for russian / chinese / arabic, 
        $options = \XF::options();
        if ($found = $this->checkBannedCharType($options->shsBannedCharsChinese, 'Chinese ', $message)) {
            return $found;
        }
        if ($found = $this->checkBannedCharType($options->shsBannedCharsRussian, 'Russian ', $message)) {
            return $found;
        }
        if ($found = $this->checkBannedCharType($options->shsBannedCharsArabic, 'Arabic ', $message)) {
            return $found;
        }
        if ($found = $this->checkBannedCharType($options->shsBannedCharsHebrew, 'Hebrew ', $message)) {
            return $found;
        }


        return false;
    }

    public function checkBannedCharType($op, $type, $message) {
        if ($op) {
            $data = preg_split('/\\] \\[|\\[|\\]/', $op, -1, PREG_SPLIT_NO_EMPTY);
            $maxChar = $data[0];
            $regex = $data[1];
            if ($maxChar > 0) {
                if ($regex && $message) {
                    preg_match_all($regex, $message, $matches);
                    if ($matches && is_array($matches) && count($matches[0]) >= $maxChar) {
                        $bwords = '';
                        foreach ($matches[0] as $match) {
                            $bwords .= ($match) . ',';
                        }

                        $rtrn = count($matches[0]) . ' matched ' . $type . ' characters: ' . ($bwords);
                        return substr($rtrn, 0, 300);
                    }
                }
            }
        }
        return false;
    }

}
