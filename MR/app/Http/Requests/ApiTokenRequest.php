<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'abilities' => 'nullable|array',
            'abilities.*' => 'string|in:*,actions:read,actions:write,actions:delete,operations:read,operations:write,operations:delete,apis:read,apis:write,apis:delete,performance:read,performance:write,dashboards:read,dashboards:write,alerts:read,alerts:write,patterns:read,patterns:write,unlimited',
            'expires_at' => 'nullable|date|after:now|before:' . now()->addYears(1)->format('Y-m-d'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Token name is required',
            'name.min' => 'Token name must be at least 3 characters',
            'name.max' => 'Token name may not exceed 255 characters',
            'abilities.*.in' => 'Invalid ability specified',
            'expires_at.after' => 'Expiration date must be in the future',
            'expires_at.before' => 'Token may not expire more than 1 year from now',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'token name',
            'abilities' => 'abilities',
            'expires_at' => 'expiration date',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function configure(): void
    {
        $this->sometimes('abilities', function ($validator) {
            $validator->after(function ($validator) {
                if ($validator->errors()->has('abilities')) {
                    return;
                }

                $abilities = $this->input('abilities', []);
                
                // Validate ability combinations
                if (in_array('*', $abilities) && count($abilities) > 1) {
                    $validator->errors()->add('abilities', 'Cannot specify both "*" and specific abilities');
                }

                // Validate write permissions require read permissions
                $writeAbilities = ['actions:write', 'actions:delete', 'operations:write', 'operations:delete', 'apis:write', 'apis:delete', 'performance:write', 'dashboards:write', 'alerts:write', 'patterns:write'];
                $readAbilities = ['actions:read', 'operations:read', 'apis:read', 'performance:read', 'dashboards:read', 'alerts:read', 'patterns:read'];

                foreach ($writeAbilities as $writeAbility) {
                    if (in_array($writeAbility, $abilities) && !in_array('*', $abilities)) {
                        $correspondingReadAbility = str_replace(['write', 'delete'], 'read', $writeAbility);
                        if (!in_array($correspondingReadAbility, $abilities) && !in_array('*', $abilities)) {
                            $validator->errors()->add('abilities', "Write ability '{$writeAbility}' requires read ability '{$correspondingReadAbility}'");
                        }
                    }
                }
            });
        });
    }
}
