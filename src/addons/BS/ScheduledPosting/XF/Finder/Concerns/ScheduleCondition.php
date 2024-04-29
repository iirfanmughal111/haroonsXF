<?php

namespace BS\ScheduledPosting\XF\Finder\Concerns;

trait ScheduleCondition
{
    /**
     * @param string $stateColumn
     * @param bool $nodeId
     */
    protected function replaceScheduleStates($stateColumn = 'discussion_state', $nodeId = false)
    {
        $conditions = $this->getConditions();

        $this->resetWhere();

        $stateKey = $this->findConditionByStrPos($conditions,$this->columnSqlName($stateColumn) . ' IN (');

        if ($stateKey !== null)
        {
            $visitor = \XF::visitor();
            $hasPermission = $nodeId ? $visitor->hasNodePermission($nodeId, 'viewScheduled') : $visitor->canViewScheduled();
            if ($hasPermission)
            {
                $conditions[$stateKey] = preg_replace('/IN \(/i', '$0' . $this->quote('scheduled') . ', ', $conditions[$stateKey]);
            }
            else if ($visitor->user_id)
            {
                $scheduleCondition = $this->buildCondition([
                    $stateColumn => 'scheduled',
                    'user_id' => $visitor->user_id
                ]);

                $conditions[$stateKey] = preg_replace('/\)\)/i', '$0 OR (' . $scheduleCondition . ')', $conditions[$stateKey]);
            }
        }

        foreach ($conditions as $condition)
        {
            $this->writeSqlCondition($condition);
        }
    }

    protected function findConditionByStrPos($conditions, $str)
    {
        foreach ($conditions as $conditionKey => $condition)
        {
            if (strpos($condition, $str) !== false)
            {
                return $conditionKey;
            }
        }

        return null;
    }
}