<?php

namespace App\LineGraphs;


class LineGraph
{
    /**
     * @var int
     */
    public static $incr = 0;
    /**
     * @var string
     */
    protected $el_id = "";
    /**
     * @var string
     */
    protected $title = "";
    /**
     * @var string
     */
    protected $height = "250px";
    /**
     * @var string[]
     */
    protected $line_colors = array();
    /**
     * @var LineGraphDataRow[]
     */
    protected $data_rows = array();
    /**
     * @var string
     */
    protected $xkey = "period";
    /**
     * @var string
     */
    protected $xLabels = "hours";

    /**
     * Get el_id
     * @see el_id
     * @return string
     */
    public function getElId(): string
    {
        if (!$this->el_id) {
            self::$incr++;
            $this->el_id = "appLineGraph" . self::$incr;
        }

        return $this->el_id;
    }

    /**
     * Set el_id
     * @see el_id
     * @param string $el_id
     * @return LineGraph
     */
    public function setElId(string $el_id): LineGraph
    {
        $this->el_id = $el_id;
        return $this;
    }

    /**
     * Get title
     * @see title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title
     * @see title
     * @param string $title
     * @return LineGraph
     */
    public function setTitle(string $title): LineGraph
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get height
     * @see height
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * Set height
     * @see height
     * @param string $height
     * @return LineGraph
     */
    public function setHeight(string $height): LineGraph
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get line_colors
     * @see line_colors
     * @return string[]
     */
    public function getLineColors(): array
    {
        return $this->line_colors;
    }

    /**
     * Set line_colors
     * @see line_colors
     * @param string[] $line_colors
     * @return LineGraph
     */
    public function setLineColors(array $line_colors): LineGraph
    {
        $this->line_colors = $line_colors;
        return $this;
    }

    /**
     * Get data_rows
     * @see data_rows
     * @return LineGraphDataRow[]
     */
    public function getDataRows(): array
    {
        return $this->data_rows;
    }

    /**
     * Set data_rows
     * @see data_rows
     * @param LineGraphDataRow[] $data_rows
     * @return LineGraph
     */
    public function setDataRows(array $data_rows): LineGraph
    {
        $this->data_rows = $data_rows;
        return $this;
    }

    /**
     * Get xkey
     * @see xkey
     * @return string
     */
    public function getXkey(): string
    {
        return $this->xkey;
    }

    /**
     * Set xkey
     * @see xkey
     * @param string $xkey
     * @return LineGraph
     */
    public function setXkey(string $xkey): LineGraph
    {
        $this->xkey = $xkey;
        return $this;
    }

    /**
     * Get xLabels
     * @see xLabels
     * @return string
     */
    public function getXLabels(): string
    {
        return $this->xLabels;
    }

    /**
     * Set xLabels
     * @see xLabels
     * @param string $xLabels
     * @return LineGraph
     */
    public function setXLabels(string $xLabels): LineGraph
    {
        $this->xLabels = $xLabels;
        return $this;
    }

    /**
     * Get Y ключи
     * @return string[]
     */
    public function getYKeys()
    {
        if (sizeof($this->data_rows)) {
            return array_keys($this->data_rows[0]->getLabels());
        }
        return array();
    }

    /**
     * Get headers
     * @return string[]
     */
    public function getLabels()
    {
        if (sizeof($this->data_rows)) {
            return array_values($this->data_rows[0]->getLabels());
        }

        return array();
    }

    /**
     * Get YMax
     * @return float
     */
    public function getYMax()
    {
        $max = null;

        foreach ($this->data_rows as $data) {

            if (!isset($max)) {
                $max = $data->getMaxValue();
            }

            $max = max($max, $data->getMaxValue());
        }

        return $max + 5;
    }

    /**
     * Get YMin
     * @return float
     */
    public function getYMin()
    {
        $min = null;

        foreach ($this->data_rows as $data) {

            if (!isset($min)) {
                $min = $data->getMinValue();
            }

            $min = min($min, $data->getMinValue());
        }

        return $min - 5;
    }

    /**
     * Add Data Row
     * @param LineGraphDataRow $data_row
     * @return $this
     */
    public function addDataRow(LineGraphDataRow $data_row)
    {
        $this->data_rows[] = $data_row;
        return $this;
    }
}