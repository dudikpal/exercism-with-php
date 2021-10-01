<?php

/*
 * By adding type hints and enabling strict type checking, code can become
 * easier to read, self-documenting and reduce the number of potential bugs.
 * By default, type declarations are non-strict, which means they will attempt
 * to change the original type to match the type specified by the
 * type-declaration.
 *
 * In other words, if you pass a string to a function requiring a float,
 * it will attempt to convert the string value to a float.
 *
 * To enable strict mode, a single declare directive must be placed at the top
 * of the file.
 * This means that the strictness of typing is configured on a per-file basis.
 * This directive not only affects the type declarations of parameters, but also
 * a function's return type.
 *
 * For more info review the Concept on strict type checking in the PHP track
 * <link>.
 *
 * To disable strict typing, comment out the directive below.
 */

declare(strict_types=1);

class Tournament
{

    const HEADLINE = 'Team                           | MP |  W |  D |  L |  P';

    public function tally($scores)
    {
        $resultString = ''.self::HEADLINE;

        if (empty($scores)) {
            return $resultString;
        }

        $inputLines = explode('\n', $scores);
        $arr = array();

        foreach ($inputLines as $part ) {

            $inputLineParts = explode(';', $part);

            $team1 =  "${inputLineParts[0]}";
            $team2 =  "${inputLineParts[1]}";

            $team1Exist = array_key_exists($team1, $arr);
            $team2Exist = array_key_exists($team2, $arr);


            if (!$team1Exist) {
                $arr = $this->initTeamTable($team1, $arr);
            }

            if (!$team2Exist) {
                $arr = $this->initTeamTable($team2, $arr);
            }

            if (strcmp($inputLineParts[2], "win") == 0) {
                $arr[$team1]['match'] += 1;
                $arr[$team1]['win'] += 1;
                $arr[$team1]['points'] += 3;
                $arr[$team2]['match'] += 1;
                $arr[$team2]['lose'] += 1;

            } else if (strcmp($inputLineParts[2], "loss") == 0) {
                $arr[$team2]['match'] += 1;
                $arr[$team2]['win'] += 1;
                $arr[$team2]['points'] += 3;
                $arr[$team1]['match'] += 1;
                $arr[$team1]['lose'] += 1;
            } else {
                $arr[$team1]['match'] += 1;
                $arr[$team1]['draw'] += 1;
                $arr[$team1]['points'] += 1;
                $arr[$team2]['match'] += 1;
                $arr[$team2]['draw'] += 1;
                $arr[$team2]['points'] += 1;
            }

        }

        array_multisort(array_column($arr, 'points'), SORT_DESC,
                                array_column($arr, 'name'), SORT_ASC,
                                $arr);

        $resultLinePattern = '\n%-31s|  %s |  %s |  %s |  %s |  %s';

        foreach ($arr as $teamRecord) {

            $resultString .= sprintf($resultLinePattern,
                                    $teamRecord['name'],
                                    $teamRecord['match'],
                                    $teamRecord['win'],
                                    $teamRecord['draw'],
                                    $teamRecord['lose'],
                                    $teamRecord['points']
            );
        }

        return $resultString;
    }

    /**
     * @param string $team1
     * @param array $arr
     * @return array
     */
    public function initTeamTable(string $team, array $arr): array
    {
        $arr[$team] = array('name' => $team,
            'match' => 0,
            'win' => 0,
            'draw' => 0,
            'lose' => 0,
            'points' => 0);
        return $arr;
    }


}
