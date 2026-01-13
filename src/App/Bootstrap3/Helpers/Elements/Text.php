<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class Text extends BaseView implements ViewInterface
{
    /**
     * Text field identifier
     * @var string
     */
    public $id;
    
    /**
     * Text field name
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
     * Required field flag
     * @var bool
     */
    public $require;
    
    /**
     * Whether the element is disabled
     * @var bool
     */
    public $disabled = false;

    /**
     * Placeholder for the element
     * @var string
     */
    public $placeholder;

    /**
     * Maximum field length
     * @var integer
     */
    public $max_length = 255;
    /**
     * Description/help text
     * @var string
     */
    public $description;
    /**
     * Flag indicating whether autocomplete is needed
     * @var string
     */
    public $auto_complete = "off";

    /**
     * Constructor
     * @param array $options
     */
    public function __construct(?array $options = null)
    {
        foreach (array("id", "name", "title", "value", "require", "placeholder", "disabled", "max_length", "description", "auto_complete") as $attribute) {
            
            if (isset($options[$attribute])) {
                
                $this->$attribute = $options[$attribute];
            }
        }

        parent::__construct();
    }

    /**
     * Get id
     * @see id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     * @see id
     * @param string $id
     * @return Text
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
     * @return Text
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
     * @return Text
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
     * @return Text
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
     * @return Text
     */
    public function setRequire($require)
    {
        $this->require = $require;
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
        return $this->disabled;
    }

    /**
     * Set disabled
     * @see disabled
     * @param bool $disabled
     * @return Text
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * Get placeholder
     * @see placeholder
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set placeholder
     * @see placeholder
     * @param string $placeholder
     * @return Text
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Get max_length
     * @see max_length
     * @return int
     */
    public function getMaxLength()
    {
        return $this->max_length;
    }

    /**
     * Set max_length
     * @see max_length
     * @param int $max_length
     * @return Text
     */
    public function setMaxLength($max_length)
    {
        $this->max_length = $max_length;
        return $this;
    }

    /**
     * Get description
     * @see description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     * @see description
     * @param string $description
     * @return Text
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get auto_complete
     * @see auto_complete
     * @return string
     */
    public function getAutoComplete(): string
    {
        return $this->auto_complete;
    }

    /**
     * Set auto_complete
     * @see auto_complete
     * @param string $auto_complete
     * @return Text
     */
    public function setAutoComplete(string $auto_complete): Text
    {
        $this->auto_complete = $auto_complete;
        return $this;
    }

    /**
     * Render/output template
     * @return void
     */
    public function out()
    {
        ?>
            	<div class="form-group">
    				<label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?><?php if ($this->require) print '<span class="text-danger">&nbsp;*</span>';?></label>
    				<div class="col-sm-9">
    					<input
                            name="<?php print $this->getName();?>"
                            id="<?php print $this->getId()?>"
                            class="form-control"
                            type="text"
                            value="<?php print $this->getValue();?>"
                            placeholder="<?php print $this->getPlaceholder();?>"
                            maxlength="<?php print $this->getMaxLength()?>"
                            <?php print ($this->isDisabled() ? " disabled" : null); ?>
                            autocomplete="<?php print $this->auto_complete;?>">

                        <?php if ($this->description):?>
                            <p class="small text-muted" style="font-style: italic"><?php print $this->description;?></p>
                        <?php endif;?>
    				</div>
    			</div>    			
            <?php         
        }
}