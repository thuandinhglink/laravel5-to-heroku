<?php

namespace Modules\ProjectSprintTask\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\EstimatedHours;

/**
 * Class ProjectSprintTaskRequest
 *
 * The Validation Rules is Defined for Project Sprint Task.
 *
 * PHP version 7.1.3
 *
 * @category  PM
 * @package   Modules\ProjectSprintTask
 * @author    Vipul Patel <vipul@chetsapp.com>
 * @copyright 2019 Chetsapp Group
 * @license   Chetsapp Private Limited
 * @version   Release: @1.0@
 * @link      http://chetsapp.com
 * @since     Class available since Release 1.0
 */
class ProjectSprintTaskRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		switch ($this->method()) {
		case 'POST': {
			return [
					// 'project_sprint_id' => 'required',
					'name'  => [
                        'required',
                        'max:'.config('core.max_length'),
                        Rule::unique(config('core.acl.project_sprint_tasks_table'))->where(
                            function ($query) {
                                return $query->where('project_sprint_id', $this->request->get('project_sprint_id'))
                                    ->where('deleted_at', null);
                            }
                        ),
                    ],
					'start_date' => 'nullable|date',
					'end_date' => 'nullable|date|after_or_equal:start_date',
					'status' => 'required',
					'type' => 'required',
					'estimated_hours' => ['nullable',new EstimatedHours]
				];
		}
		case 'PUT':
		case 'PATCH': {
			return [
					// 'project_sprint_id' => 'required',
					'name'  => [
                        'required',
                        'max:'.config('core.max_length'),
                        Rule::unique(config('core.acl.project_sprint_tasks_table'))->where(
                            function ($query) {
                                return $query->whereNotIn('id', [$this->request->get('id')])
                                    ->where('project_sprint_id', $this->request->get('project_sprint_id'))
                                    ->where('deleted_at', null);
                            }
                        ),
                    ],
					'start_date' => 'nullable|date',
					'end_date' => 'nullable|date|after_or_equal:start_date',
					'status' => 'required',
					'type' => 'required',
					'estimated_hours' => ['nullable',new EstimatedHours]
				];
		}
		default:
			return [];
		}
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
