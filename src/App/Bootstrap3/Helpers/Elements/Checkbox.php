<?php 
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

/**
 * Class Checkbox
 * @package AV\Dashboard\Views\Form\Elements
 */
class Checkbox extends BaseView implements ViewInterface
{
    /**
     * Element identifier
     * @var string
     */
    public $id;
    
    /**
     * Field name
     * @var string
     */
    public $name;
    
    /**
     * Title/label
     * @var string
     */
    public $title;
    
    /**
     * Required flag
     * @var bool
     */
    public $require;
    
    /**
     * Current value
     * @var string
     */
    public $value = "1";
    
    /**
     * Is the checkbox checked
     * @var bool
     */
    public $checked;

    /**
     * Field description
     * @var string
     */
    public $description;
    
    /**
     * Constructor
     * @param array|null $attributes
     */
    public function __construct(?array $attributes = null)
    {
        if (! \is_null($attributes)) {
            
            foreach (array("name", "title", "require", "value", "checked") as $attribute) {
                
                if (isset($attributes[$attribute])) {
                    
                    $this->$attribute = $attributes[$attribute];
                }
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
     * @return Checkbox
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
     * @return Checkbox
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
     * @return Checkbox
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return Checkbox
     */
    public function setRequire($require)
    {
        $this->require = $require;
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
     * @return Checkbox
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get checked
     * @see checked
     * @return bool
     */
    public function isChecked()
    {
        return $this->checked;
    }

    /**
     * Set checked
     * @see checked
     * @param bool $checked
     * @return Checkbox
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
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
     * @return Checkbox
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Render output
     */
    public function out()
    {
        ?>
    	        <div class="checkbox">
    				<label>
    					<input type="checkbox" id="<?php print $this->getId()?>" name="<?php print $this->getName()?>" value="<?php print $this->getValue()?>" <?php if ($this->isChecked()) print " checked";?> /> <?php print $this->getTitle()?>
                        <?php if ($this->description):?>
                            <div class="text-muted"><?php print $this->description?></div>
                        <?php endif;?>
    				</label>
    			</div>
            <?php         
        }
 
}