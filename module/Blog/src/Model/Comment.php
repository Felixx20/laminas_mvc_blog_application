<?php

namespace Blog\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;

class Comment implements InputFilterAwareInterface
{
    public $commentID;
    public $text;
    public $authorID;
    public $postID;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->commentID   = !empty($data['commentID']) ? $data['commentID'] : null;
        $this->text     = !empty($data['text']) ? $data['text'] : null;
        $this->authorID    = !empty($data['authorID']) ? $data['authorID'] : null;
        $this->postID    =  !empty($data['postID']) ? $data['postID'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        /*
        $inputFilter->add([
            'name' => 'postID',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'authorID',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
*/
        $inputFilter->add([
            'name' => 'text',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 2000,
                    ],
                ],
            ],
        ]);



        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}