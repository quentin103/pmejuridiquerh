<?php

namespace App\Http\Requests\SmtpSetting;

use App\Http\Requests\CoreRequest;

class UpdateSmtpSetting extends CoreRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        // WORKSUITESAAS
        if (!user()->is_superadmin) {
            return [];
        }

        return [
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required_if:mail_driver,smtp',
            'mail_from_name' => 'required',
            'mail_from_email' => 'required|email:rfc,strict',
            'mail_encryption' => 'required'
        ];
    }
}
