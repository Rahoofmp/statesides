<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_jobs extends Base_Controller 
{

	function __construct()
	{
		parent::__construct();		
	}

        //Reward upgrade and reward bonus
    function reward_runs()
    {   
        $cron_id = $this->Cron_jobs_model->insertCronHistory('reward_runs');
        $this->Cron_jobs_model->begin();
        $user_details = $this->Cron_jobs_model->getAffilatePackageUpgradesDetails();
        $status  = $this->Cron_jobs_model->runUserRewards($user_details);
        if ($status) {
            $this->Cron_jobs_model->commit();
            $this->Cron_jobs_model->updateCronHistory($cron_id, "finished");
        } else {
            $this->Cron_jobs_model->rollback();
            $this->Cron_jobs_model->updateCronHistory($cron_id, "failed");
        }
    }  

    public function status_change()
    {        
        $this->Cron_jobs_model->begin();

        $status = $this->Cron_jobs_model->StatusChange();

        if ($status) {
            echo "Success";
            $this->Cron_jobs_model->commit();
        } else {
            echo "Failed";
            $this->Cron_jobs_model->rollback();
        }
        
    }

}