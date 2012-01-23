<?php
namespace Application\Model\Resumator;

class Feed
{

    /**
     * @var \SimpleXMLElement
     */
    protected $xml;

    protected $publisher;
    protected $publisherUrl;
    protected $lastBuildDate;
    protected $company;
    protected $jobs;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    public function getPublisher()
    {
        if (!isset($this->publisher)) {
            $result = $this->xml->xpath('publisher');
            if (isset($result[0])) {
                $this->publisher = (string) $result[0];
            }
        }
        return $this->publisher;
    }

    public function getPublisherUrl()
    {
        if (!isset($this->publisherUrl)) {
            $result = $this->xml->xpath('publisherurl');
            if (isset($result[0])) {
                $this->publisherUrl = (string) $result[0];
            }
        }
        return $this->publisherUrl;
    }

    public function getLastBuildDate()
    {
        if (!isset($this->lastBuildDate)) {
            $result = $this->xml->xpath('lastBuildDate');
            if (isset($result[0])) {
                $this->lastBuildDate = (string) $result[0];
            }
        }
        return $this->lastBuildDate;
    }

    public function getCompany()
    {
        if (!isset($this->company)) {
            $result = $this->xml->xpath('company');
            if (isset($result[0])) {
                $this->company = (string) $result[0];
            }
        }
        return $this->company;
    }

    public function getJobs()
    {
        if (!isset($this->jobs)) {
            $result = $this->xml->xpath('job');
            if (is_array($result)) {
                foreach ($result as $job) {
                    $this->jobs[] = new Job($job);
                }
            }
        }
        return $this->jobs;
    }

    public function getJobsByFilter($filter, $value)
    {
        $return = null;
        $result = $this->xml->xpath("job[$filter='$value']");
        if (is_array($result)) {
            foreach ($result as $job) {
                $return[] = new Job($job);
            }
        }
        return $return;
    }
}