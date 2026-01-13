<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class Textarea extends BaseView implements ViewInterface
{
    public $id;
    
    /**
     * Textarea field name
     * @var string
     */
    public $name;
    
    /**
     * Label/title
     * @var string
     */
    public $title;
    
    /**
     * Current form value
     * @var string
     */
    public $value;

    /**
     * Required flag
     * @var boolean
     */
    public $require;
    
    /**
     * Width of the textarea block
     * @var string
     */
    public $width = "100%";
    
    /**
     * Height of the textarea block
     * @var string
     */
    public $height = "400px";
    
    /**
     * Whether the element is disabled
     * @var boolean
     */
    public $disabled = false;
    
    /**
     * Constructor
     * @param array $options
     */
    public function __construct(?array $options = null)
    {
        foreach (array("id", "name", "title", "require", "value", "disabled") as $attribute) {
            
            if (isset($options[$attribute])) {
                
                $this->$attribute = $options[$attribute];
            }
        }

        parent::__construct();
    }

    /**
     * Get id
     * @see id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     * @see id
     * @param mixed $id
     * @return Textarea
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get name
     * @see name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * @see name
     * @param string $name
     * @return Textarea
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get title
     * @see title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     * @see title
     * @param string $title
     * @return Textarea
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get value
     * @see value
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     * @see value
     * @param string $value
     * @return Textarea
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get require
     * @see require
     * @return bool
     */
    public function isRequire()
    {
        return $this->require;
    }

    /**
     * Set require
     * @see require
     * @param bool $require
     * @return Textarea
     */
    public function setRequire($require)
    {
        $this->require = $require;
        return $this;
    }

    /**
     * Get width
     * @see width
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     * @see width
     * @param string $width
     * @return Textarea
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Get height
     * @see height
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height
     * @see height
     * @param string $height
     * @return Textarea
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Get disabled
     * @see disabled
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * Get disabled
     * @see disabled
     * @return bool
     */
    public function getDisabled()
    {
        return $this->isDisabled();
    }

    /**
     * Set disabled
     * @see disabled
     * @param bool $disabled
     * @return Textarea
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }
    
    /**
     * Render output
     */
    public function out()
    {
        ?>
            	<div class="form-group">
    				<label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?></label>
    				<div class="col-sm-9">
    					<textarea name="<?php print $this->getName();?>" class="form-control" rows="5" id="<?php print $this->getId()?>" style="max-width:100%;"<?php print ($this->getDisabled() ? " disabled" : null);?>><?php print $this->getValue();?></textarea>
    				</div>
    			</div>    			
            <?php         
        }
}