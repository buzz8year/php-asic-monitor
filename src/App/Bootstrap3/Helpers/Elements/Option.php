<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class Option extends BaseView implements ViewInterface
{
    public $id;
    
    /**
     * @var string
     */
    public $title;
    
    /**
     * @var string
     */
    public $value;
    
    /**
     * @var boolean
     */
    public $selected = false;
    
    /**
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        if (!\is_null($options)) {
            
            foreach (array("id", "title", "value", "selected") as $attribute) {
                
                if (isset($options[$attribute])) {
                    
                    $this->$attribute = $options[$attribute];
                }
            }
        }

        parent::__construct();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getSelected()
    {
        return $this->selected;
    }

    public function setSelected($selected)
    {
        $this->selected = $selected;
        return $this;
    }
    
    /**
     * Вывод шаблона
     */
    public function out()
    {
        ?> <option id="<?php print $this->getId()?>" value="<?php print $this->getValue()?>"<?php if ($this->getSelected()) print " selected"; ?>><?php print $this->getTitle();?></option> <?php
    }
 
    
   
}