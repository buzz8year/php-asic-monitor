<?php

namespace App\LineGraphs;

class LineGraphDataRow
{
    /**
    * Period for display
     * @var string
     */
    public $period;
    /**
    * Returns labels
     * @var string[]
     */
    protected $labels = array();
    /**
    * Minimum value
     * @var null|float
     */
    protected $min_value = null;
    /**
    * Maximum value
     * @var null|float
     */
    protected $max_value = null;

    /**
     * Returns period
     * @see period
     * @return string
     */
    public function getPeriod(): string
    {
        return $this->period;
    }

    /**
     * Sets period
     * @see period
     * @param string $period
     * @return LineGraphDataRow
     */
    public function setPeriod(string $period): LineGraphDataRow
    {
        $this->period = $period;
        return $this;
    }

    /**
     * Returns labels
     * @see labels
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * Returns min_value
     * @see min_value
     * @return float|null
     */
    public function getMinValue()
    {
        return $this->min_value;
    }

    /**
     * Sets min_value
     * @see min_value
     * @param float|null $min_value
     * @return LineGraphDataRow
     */
    public function setMinValue($min_value)
    {
        $this->min_value = $min_value;
        return $this;
    }

    /**
     * Returns max_value
     * @see max_value
     * @return float|null
     */
    public function getMaxValue()
    {
        return $this->max_value;
    }

    /**
     * Sets max_value
     * @see max_value
     * @param float|null $max_value
     * @return LineGraphDataRow
     */
    public function setMaxValue($max_value)
    {
        $this->max_value = $max_value;
        return $this;
    }

    /**
     * Adds an entry
     * @param string $label
     * @param string $key
     * @param number $value
     * @return LineGraphDataRow
     */
    public function addEntry(string $label, string $key, $value): LineGraphDataRow
    {
        $this->labels[$key] = $label;
        $this->$key = $value;

        if (!isset($this->min_value) || !isset($this->max_value)) {
            $this->min_value = $value;
            $this->max_value = $value;
        }

        $this->min_value = min($this->min_value, $value);
        $this->max_value = max($this->max_value, $value);

        return $this;
    }


}