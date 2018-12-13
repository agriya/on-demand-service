<?php
namespace Models;

class UserProfile extends AppModel
{
    /**
     * @var string
     */
    protected $table = "user_profiles";
    protected $casts = [
        'user_id' => 'integer',
        'language_id' => 'integer',
        'gender_id' => 'integer',
        'listing_city_id' => 'integer',
        'listing_state_id' => 'integer',
        'listing_country_id' => 'integer',
        'notification_type_id' => 'integer',
        'is_listing_updated' => 'integer',
        'is_online_assessment_test_completed' => 'integer',
        'listing_latitude' => 'double',
        'listing_longitude' => 'double',        
        'listing_status_id' => 'integer',
        'is_service_profile_updated' => 'integer',
        'photo_count' => 'integer',
        'services_user_count' => 'integer',
        'is_listing_address_verified' => 'integer',
        'repeat_client_count' => 'integer',
        'completed_appointment_count' => 'integer',
        'response_rate' => 'integer',
        'response_time' => 'integer',
        'listing_appointment_count' => 'integer',
        'site_revenue_as_service_provider' => 'double', 
        'earned_amount_as_service_provider' => 'double',
        'total_spent_amount_as_customer' => 'double',
        'user_view_count' => 'integer',
        'user_favorite_count' => 'integer', 
        'is_top_listed' =>   'integer'
    ];    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'attachment_id', 'first_name', 'last_name', 'display_name', 'gender_id', 'about_me', 'awards', 'phone', 'listing_address', 'listing_city_id', 'listing_country_id', 'listing_postal_code', 'listing_address1', 'notification_type_id', 'listing_latitude', 'listing_longitude', 'listing_state_id', 'dob', 'listing_title', 'listing_description', 'is_listing_updated', 'is_online_assessment_test_completed', 'listing_status_id', 'is_listing_address_verified', 'listing_appointment_count', 'user_view_count', 'user_favorite_count','top_user_expiry','is_top_listed', 'pro_account_status_id', 'verification_note_by_site', 'driving_license_information', 'cv', 'is_available_for_interview', 'hourly_rate_for_interview', 'is_available_via_skype_interview', 'im_skype', 'is_available_via_phone_interview', 'is_available_via_in_person_interview'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender()
    {
        return $this->belongsTo('Models\Gender', 'gender_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing_city()
    {
        return $this->belongsTo('Models\City', 'listing_city_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing_state()
    {
        return $this->belongsTo('Models\State', 'listing_state_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing_country()
    {
        return $this->belongsTo('Models\Country', 'listing_country_id', 'id');
    }
    public function listing_status()
    {
        return $this->belongsTo('Models\ListingStatus', 'listing_status_id', 'id');
    }
    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }
    public $rules = array(
        'first_name' => 'sometimes|required|min:3',
        'last_name' => 'sometimes|required|min:1',
        'listing_city_id' => 'sometimes|required',
        'listing_state_id' => 'sometimes|required',
        'listing_country_id' => 'sometimes|required',
        'gender_id' => 'sometimes|required',
        'dob' => 'sometimes|required'
    );
}
