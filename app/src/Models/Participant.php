<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
  protected $table = "participant";
  protected $primaryKey = "idParticipant";
  public $timestamps = false;
}
