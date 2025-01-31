<div class="card">
    <div class="card-header">
        <h4 class="h4">Voice Upload Webhook Setting Url </h4>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">
            <ul>
                <li>Add below url to your webhook url.</li>
                <li>key "message" is reqired while hitting webhook in custom data array.</li>
                <li>key "voice_id" is optional in custom data array </li>
                <li>if its exist then this voice id will be sent to GHL else location's custom selected voice_id will be use. </li>
            </ul>
        </div>
        <div class="copy-container">
            <input type="text" class="form-control code_url"
                value="{{ route('webhook.text.speech') }}"
                readonly>
            <div class="row my-2">
                <div class="col-md-12" style="text-align: left !important">
                    <button type="button" class="btn btn-primary script_code copy_url" data-message="Link Copied"
                        id="kt_account_profile_details_submit">Copy URL</button>
                </div>
            </div>
        </div>
    </div>
</div>
