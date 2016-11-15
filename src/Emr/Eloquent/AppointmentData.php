<?php

namespace LibreEHR\Core\Emr\Eloquent;

use LibreEHR\Core\Emr\Eloquent\AbstractModel as Model;
use Illuminate\Support\Facades\DB;
use LibreEHR\Core\Contracts\AppointmentInterface;

class AppointmentData extends Model implements AppointmentInterface
{
    protected $connection = 'mysql';

    protected $table = 'libreehr_postcalendar_events';

    protected $primaryKey = 'pc_eid';

    protected $listId = 'apptstat';

    public $timestamps = false;

    public function getId()
    {
        return $this->pc_eid;
    }
    public function setId( $id )
    {
        $this->pc_eid = $id;
        return $this;
    }

    public function getPatientId()
    {
        return $this->pc_pid;
    }
    public function setPatientId($id)
    {
        $this->pc_pid = $id;
        return $this;
    }

    public function getPcTitle()
    {
        return $this->pc_title;
    }
    public function setPcTitle($title)
    {
        $this->pc_title = $title;
        return $this;
    }

    public function getPcInformant()
    {
        return $this->pc_informant;
    }
    public function setPcInformant($informant)
    {
        $this->pc_informant = $informant;
        return $this;
    }

    public function getPcCatid()
    {
        return $this->pc_catid;
    }
    public function setPcCatid($pcCatid)
    {
        $this->pc_catid = $pcCatid;
        return $this;
    }

    public function getPcEventStatus()
    {
        return $this->pc_eventstatus;
    }
    public function setPcEventStatus($pcEventStatus)
    {
        $this->pc_eventstatus = $pcEventStatus;
        return $this;
    }

    public function getProviderId()
    {
        return $this->pc_aid;
    }

    public function setProviderId( $providerId )
    {
        $this->pc_aid = $providerId;
        return $this;
    }

    public function getStartTime()
    {
        return strtotime($this->getPcEventDate() . ' ' . $this->pc_startTime);
    }

    public function setStartTime( $startTime )
    {
        $this->pc_startTime = $this->timeFromTimestamp($startTime);
        return $this;
    }

    public function getEndTime()
    {
        return $this->getStartTime() + ( $this->getPcDuration() * 60 );
    }

    public function setEndTime( $endTime )
    {
        $this->pc_endTime = $this->timeFromTimestamp($endTime);
        return $this;
    }

    public function getPcEventDate()
    {
        return $this->pc_eventDate;
    }

    public function setPcEventDate($pcEventDate)
    {
        $this->pc_eventDate = $this->dateFromTimestamp($pcEventDate);
        return $this;
    }

    public function getPcEndDate()
    {
        return $this->pc_endDate;
    }

    public function setPcEndDate($pcEndDate)
    {
        $this->pc_endDate = $this->dateFromTimestamp($pcEndDate);
        return $this;
    }

    public function getPcApptStatus()
    {
        return $this->decodeStatus($this->pc_apptstatus);
    }

    public function setPcApptStatus($pcApptstatus)
    {
        $this->pc_apptstatus = $this->encodeStatus($pcApptstatus);
        return $this;
    }

    public function getPcDuration()
    {
        return $this->pc_duration;
    }

    public function setPcDuration($pcDuration)
    {
        $this->pc_duration = $pcDuration;
        return $this;
    }

    public function getDescription()
    {
        return $this->pc_hometext;
    }

    public function setDescription($description)
    {
        $this->pc_hometext = $description;
        return $this;
    }

    public function getPcTime()
    {
        return strtotime($this->pc_time);
    }
    
    public function setPcTime($time)
    {
        $this->pc_time = date('Y-m-d H:m:i', $time);
        return $this;
    }

    public function getServiceType()
    {
        return $this->pc_title;
    }

    public function setServiceType($serviceType)
    {
        $this->pc_title = $serviceType;
        return $this;
    }

    public function getLocation()
    {
        return $this->pc_location;
    }

    public function setLocation($location)
    {
        $this->pc_location = $location;
        return $this;
    }
    public function getPcMultiple()
    {
        return $this->pc_multiple;
    }

    public function setPcMultiple($pc_multiple)
    {
        $this->pc_multiple = $pc_multiple;
        return $this;
    }

    private function decodeStatus($status)
    {
        $conditions= [
            0 => ['option_id', 'like', $status],
            1 => ['list_id', 'like', $this->listId]
        ];
        return DB::connection($this->connection)->table('list_options')->where($conditions)->value('mapping');
    }

    private function encodeStatus($status)
    {
        $conditions= [
            0 => ['mapping', 'like', $status],
            1 => ['list_id', 'like', $this->listId]
        ];
        return DB::connection($this->connection)->table('list_options')->where($conditions)->value('option_id');
    }

    private function timeFromTimestamp($timestamp)
    {
        return date('H:i:s', $timestamp);
    }

    private function dateFromTimestamp($timestamp)
    {
        return date('Y-m-d', $timestamp);
    }

    private function timeToTimestamp($timestamp)
    {
        return date('H:i:s', $timestamp);
    }

    private function dateToTimestamp($timestamp)
    {
        return date('Y-m-d', $timestamp);
    }

}
