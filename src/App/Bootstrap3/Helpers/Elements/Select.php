<?php
namespace App\Bootstrap3\Helpers\Elements;

use App\Views\BaseView;
use App\Views\ViewInterface;

class Select extends BaseView implements ViewInterface
{
    /**
     * Element identifier
     * @var string
     */
    public $id;

    /**
     * Element name
     * @var string
     */
    public $name;

    /**
     * Element title
     * @var string
     */
    public $title;

    /**
     * List options
     * @var Option[]
     */
    public $options = array();

    /**
     * Whether the field is required
     * @var bool
     */
    public $require;

    /**
     * Additional CSS class
     * @var string
     */
    public $select_class = "form-control";

    /**
     * Value
     * @var string
     */
    public $value;

    /**
     * Select constructor.
     * @param array|null $attributes
     */
    public function __construct(?array $attributes = null)
    {
        if (!\is_null( $attributes )) {
             
            foreach (array("id", "name", "title", "options", "require", "value") as $attribute) {
        
                if (isset($attributes[$attribute])) {
                    	
                    $this->$attribute = $attributes[$attribute];
                }
            }
        }

        parent::__construct();
    }

    /**
     * Returns id
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
     * @return Select
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
     * @return Select
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
     * @return Select
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param Option $option
     * @return Select
     */
    public function addOption(Option $option)
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * Get options
     * @see options
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options
     * @see options
     * @param Option[] $options
     * @return Select
     */
    public function setOptions($options)
    {
        $this->options = $options;
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
     * @return Select
     */
    public function setRequire($require)
    {
        $this->require = $require;
        return $this;
    }

    /**
     * Get select_class
     * @see select_class
     * @return string
     */
    public function getSelectClass()
    {
        return $this->select_class;
    }

    /**
     * Set select_class
     * @see select_class
     * @param string $select_class
     * @return Select
     */
    public function setSelectClass($select_class)
    {
        $this->select_class = $select_class;
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
     * @return Select
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return void
     */
    public function out()
    {
        ?>

            <?php if ($this->title):?>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="<?php print $this->getId()?>"><?php print $this->getTitle()?><?php if ($this->require) print '<span class="text-danger">&nbsp;*</span>';?></label>
                    <div class="col-sm-9">
                        <select class="<?php print $this->select_class;?>" name="<?php print $this->getName()?>" id="<?php print $this->getId()?>" style="width: 100%;">
                            <?php
                            foreach ( $this->getOptions() as $option ) {

                                if ($option->value == $this->value) {

                                    $option->setSelected(true);
                                }

                                  $option->out();
                            }
                            ?>
                        </select>
                    </div>
                </div>

            <?php else:?>

                <select
                        class="<?php print $this->select_class;?>"
                        name="<?php print $this->getName()?>"
                        <?php if ($this->getId()):?>id="<?php print $this->getId()?>"<?php endif;?>
                        style="width: 100%;"
                >
                    <?php
                    foreach ( $this->getOptions() as $option ) {

                        if ($option->value == $this->value) {

                            $option->setSelected(true);
                        }

                        $option->out();
                    }
                    ?>
                </select>

            <?php endif;?>
        <?php
    }



}