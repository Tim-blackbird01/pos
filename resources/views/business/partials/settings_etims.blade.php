<div class="pos-tab-content">
    <div class="row">
        <div class="col-xs-12"><div class="checkbox"><label>
            {!! Form::checkbox('etims_settings[enabled]', 1, !empty($etims_settings['enabled']), ['class' => 'input-icheck']) !!}
            Enable eTIMS submissions for this business
        </label></div></div>
        <div class="col-xs-4"><div class="form-group">
            {!! Form::label('etims_environment', 'Environment:') !!}
            {!! Form::select('etims_settings[environment]', ['sandbox' => 'Sandbox', 'production' => 'Production'], $etims_settings['environment'] ?? 'sandbox', ['class' => 'form-control', 'id' => 'etims_environment']) !!}
        </div></div>
        <div class="col-xs-4"><div class="form-group">
            {!! Form::label('etims_tin', 'KRA PIN / TIN:') !!}
            {!! Form::text('etims_settings[tin]', $etims_settings['tin'] ?? null, ['class' => 'form-control', 'placeholder' => 'e.g. P012345678X']) !!}
        </div></div>
        <div class="col-xs-4"><div class="form-group">
            {!! Form::label('etims_branch_id', 'Branch ID:') !!}
            {!! Form::text('etims_settings[branch_id]', $etims_settings['branch_id'] ?? '00', ['class' => 'form-control', 'placeholder' => '00']) !!}
        </div></div>
        <div class="col-xs-6"><div class="form-group">
            {!! Form::label('etims_device_serial_number', 'OSCU device serial number:') !!}
            {!! Form::text('etims_settings[device_serial_number]', $etims_settings['device_serial_number'] ?? null, ['class' => 'form-control']) !!}
        </div></div>
        <div class="col-xs-6"><div class="form-group">
            {!! Form::label('etims_communication_key', 'Communication key:') !!}
            {!! Form::password('etims_settings[communication_key]', ['class' => 'form-control', 'placeholder' => !empty($etims_settings['communication_key']) ? 'Saved securely — enter a new key to replace it' : 'KRA communication key']) !!}
            @if(!empty($etims_settings['communication_key']))<span class="help-block">A communication key is already saved securely. Leave this blank to keep it.</span>@endif
        </div></div>
        <div class="col-xs-4"><div class="form-group">
            {!! Form::label('etims_item_classification_code', 'Item classification code:') !!}
            {!! Form::text('etims_settings[item_classification_code]', $etims_settings['item_classification_code'] ?? null, ['class' => 'form-control']) !!}
        </div></div>
        <div class="col-xs-4"><div class="form-group">
            {!! Form::label('etims_item_code_prefix', 'Item code prefix (optional):') !!}
            {!! Form::text('etims_settings[item_code_prefix]', $etims_settings['item_code_prefix'] ?? null, ['class' => 'form-control']) !!}
        </div></div>
        <div class="col-xs-4"><div class="form-group">
            {!! Form::label('etims_default_tax_code', 'Default tax code:') !!}
            {!! Form::text('etims_settings[default_tax_code]', $etims_settings['default_tax_code'] ?? 'B', ['class' => 'form-control']) !!}
        </div></div>
    </div>
</div>
