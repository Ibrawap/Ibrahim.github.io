<?php
namespace Application\Model\Resumator;

class Job
{

    /**
     * @var \SimpleXMLElement
     */
    protected $xml;

    protected $id;
    protected $status;
    protected $title;
    protected $department;
    protected $url;
    protected $city;
    protected $state;
    protected $country;
    protected $postalCode;
    protected $description;
    protected $type;
    protected $experience;
    protected $buttons;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    public function getId()
    {
        if (!isset($this->id)) {
            $result = $this->xml->xpath('id');
            if (isset($result[0])) {
                $this->id = (string) $result[0];
            }
        }
        return $this->id;
    }

    public function getStatus()
    {
        if (!isset($this->status)) {
            $result = $this->xml->xpath('status');
            if (isset($result[0])) {
                $this->status = (string) $result[0];
            }
        }
        return $this->status;
    }

    public function getTitle()
    {
        if (!isset($this->title)) {
            $result = $this->xml->xpath('title');
            if (isset($result[0])) {
                $this->title = (string) $result[0];
            }
        }
        return $this->title;
    }

    public function getDepartment()
    {
        if (!isset($this->department)) {
            $result = $this->xml->xpath('department');
            if (isset($result[0])) {
                $this->department = (string) $result[0];
            }
        }
        return $this->department;
    }

    public function getUrl()
    {
        if (!isset($this->url)) {
            $result = $this->xml->xpath('url');
            if (isset($result[0])) {
                $this->url = (string) $result[0];
            }
        }
        return $this->url;
    }

    public function getCity()
    {
        if (!isset($this->city)) {
            $result = $this->xml->xpath('city');
            if (isset($result[0])) {
                $this->city = (string) $result[0];
            }
        }
        return $this->city;
    }

    public function getState()
    {
        if (!isset($this->state)) {
            $result = $this->xml->xpath('state');
            if (isset($result[0])) {
                $this->state = (string) $result[0];
            }
        }
        return $this->state;
    }

    public function getCountry()
    {
        if (!isset($this->country)) {
            $result = $this->xml->xpath('country');
            if (isset($result[0])) {
                $this->country = (string) $result[0];
            }
        }
        return $this->country;
    }

    public function getPostalCode()
    {
        if (!isset($this->postalCode)) {
            $result = $this->xml->xpath('postalcode');
            if (isset($result[0])) {
                $this->postalCode = (string) $result[0];
            }
        }
        return $this->postalCode;
    }

    public function getDescription()
    {
        if (!isset($this->description)) {
            $result = $this->xml->xpath('description');
            if (isset($result[0])) {
                $this->description = (string) $result[0];
            }
        }
        return $this->description;
    }

    public function getType()
    {
        if (!isset($this->type)) {
            $result = $this->xml->xpath('type');
            if (isset($result[0])) {
                $this->type = (string) $result[0];
            }
        }
        return $this->type;
    }

    public function getExperience()
    {
        if (!isset($this->experience)) {
            $result = $this->xml->xpath('experience');
            if (isset($result[0])) {
                $this->experience = (string) $result[0];
            }
        }
        return $this->experience;
    }

    public function getButtons()
    {
        if (!isset($this->buttons)) {
            $result = $this->xml->xpath('buttons');
            if (isset($result[0])) {
                $this->buttons = (string) $result[0];
            }
        }
        return $this->buttons;
    }
}