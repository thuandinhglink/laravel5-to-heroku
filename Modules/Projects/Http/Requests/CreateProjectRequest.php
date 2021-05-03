<?php

namespace Modules\Projects\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EstimatedHours;

/**
 * Class CreateProjectRequest
 *
 * The Validation Rules is Defined for Project.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\Projects
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class CreateProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_name' => 'required|max:'.config('core.max_length').'|unique:'. config('core.acl.projects_table').',project_name,NULL,id,deleted_at,NULL',
            'project_version' => 'required',
            'status' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'billing_type' => 'nullable|integer',
            'estimated_hours' => ['nullable',new EstimatedHours],
            'price_rate' => 'nullable|numeric'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
