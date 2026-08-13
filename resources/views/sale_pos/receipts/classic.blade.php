<!-- business information here -->

<div class="row receipt-reference-header" style="color: #000000 !important;">
		<!-- Logo -->
		@if(empty($receipt_details->letter_head))
			@if(!empty($receipt_details->logo))
				<img style="max-height: 120px; width: auto;" src="{{$receipt_details->logo}}" class="img img-responsive center-block">
			@endif

			<!-- Header text -->
			@if(!empty($receipt_details->header_text))
				<div class="col-xs-12">
					{!! $receipt_details->header_text !!}
				</div>
			@endif

			<!-- business information here -->
			<div class="col-xs-12 text-center">
				<h2 class="text-center">
					<!-- Shop & Location Name  -->
					@if(!empty($receipt_details->display_name))
						{{$receipt_details->display_name}}
					@endif
				</h2>

				<!-- Address -->
				<p>
				@if(!empty($receipt_details->address))
						<small class="text-center">
						{!! $receipt_details->address !!}
						</small>
				@endif
				@if(!empty($receipt_details->contact))
					<br/>{!! $receipt_details->contact !!}
				@endif	
				@if(!empty($receipt_details->contact) && !empty($receipt_details->website))
					, 
				@endif
				@if(!empty($receipt_details->website))
					{{ $receipt_details->website }}
				@endif
				@if(!empty($receipt_details->location_custom_fields))
					<br>{{ $receipt_details->location_custom_fields }}
				@endif
				</p>
				<p>
				@if(!empty($receipt_details->sub_heading_line1))
					{{ $receipt_details->sub_heading_line1 }}
				@endif
				@if(!empty($receipt_details->sub_heading_line2))
					<br>{{ $receipt_details->sub_heading_line2 }}
				@endif
				@if(!empty($receipt_details->sub_heading_line3))
					<br>{{ $receipt_details->sub_heading_line3 }}
				@endif
				@if(!empty($receipt_details->sub_heading_line4))
					<br>{{ $receipt_details->sub_heading_line4 }}
				@endif		
				@if(!empty($receipt_details->sub_heading_line5))
					<br>{{ $receipt_details->sub_heading_line5 }}
				@endif
				</p>
				<p>
				@if(!empty($receipt_details->tax_info1))
					<b>{{ $receipt_details->tax_label1 }}</b> {{ $receipt_details->tax_info1 }}
				@endif

				@if(!empty($receipt_details->tax_info2))
					<b>{{ $receipt_details->tax_label2 }}</b> {{ $receipt_details->tax_info2 }}
				@endif
				</p>
			@endif


			<!-- Title of receipt -->
			@if(!empty($receipt_details->invoice_heading))
				<h3 class="text-center receipt-reference-title">
					{!! $receipt_details->invoice_heading !!}
				</h3>
			@endif
		</div>
		@if(!empty($receipt_details->letter_head))
			<div class="col-xs-12 text-center">
				<img style="width: 100%;margin-bottom: 10px;" src="{{$receipt_details->letter_head}}">
			</div>
		@endif
	<div class="col-xs-12 text-center receipt-reference-meta">
		<!-- Invoice  number, Date  -->
		<p style="width: 100% !important" class="word-wrap">
			<span class="pull-left text-left word-wrap">
				@if(!empty($receipt_details->invoice_no_prefix))
					<b>{!! $receipt_details->invoice_no_prefix !!}</b>
				@endif
				{{$receipt_details->invoice_no}}
				@if(!empty($receipt_details->payments))
					<br/>
					<b>Payment:</b>
					@php
						$receipt_payment_methods = collect($receipt_details->payments)
							->reject(function ($payment) {
								return !empty($payment['is_return']);
							})
							->pluck('method')
							->filter()
							->unique()
							->values();
					@endphp
					@foreach($receipt_payment_methods as $payment_method)
						@if(!$loop->first), @endif{{ $payment_method }}
					@endforeach
				@endif

				@if(!empty($receipt_details->types_of_service))
					<br/>
					<span class="pull-left text-left">
						<strong>{!! $receipt_details->types_of_service_label !!}:</strong>
						{{$receipt_details->types_of_service}}
						<!-- Waiter info -->
						@if(!empty($receipt_details->types_of_service_custom_fields))
							@foreach($receipt_details->types_of_service_custom_fields as $key => $value)
								<br><strong>{{$key}}: </strong> {{$value}}
							@endforeach
						@endif
					</span>
				@endif

				<!-- Table information-->
		        @if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
		        	<br/>
					<span class="pull-left text-left">
						@if(!empty($receipt_details->table_label))
							<b>{!! $receipt_details->table_label !!}</b>
						@endif
						{{$receipt_details->table}}

						<!-- Waiter info -->
					</span>
		        @endif

				<!-- customer info -->
				@if(!empty($receipt_details->customer_name))
					<br/>
					<b>{{ $receipt_details->customer_label ?: __('contact.customer') }}:</b> {{ $receipt_details->customer_name }}
				@endif
				@if(!empty($receipt_details->client_id_label))
					<br/>
					<b>{{ $receipt_details->client_id_label }}</b> {{ $receipt_details->client_id }}
				@endif
				@if(!empty($receipt_details->customer_tax_label))
					<br/>
					<b>{{ $receipt_details->customer_tax_label }}</b> {{ $receipt_details->customer_tax_number }}
				@endif
				@if(!empty($receipt_details->customer_custom_fields))
					<br/>{!! $receipt_details->customer_custom_fields !!}
				@endif
				@if(!empty($receipt_details->sales_person_label) && !empty($receipt_details->sales_person) && $receipt_details->sales_person != ($receipt_details->service_staff ?? null))
					<br/>
					<b>{{ $receipt_details->sales_person_label }}</b> {{ $receipt_details->sales_person }}
				@endif
				@if(!empty($receipt_details->service_staff))
					<br/>
					<b>Served by:</b> {{ $receipt_details->service_staff }}
				@elseif(!empty($receipt_details->sales_person))
					<br/>
					<b>Served by:</b> {{ $receipt_details->sales_person }}
				@endif
				@if(!empty($receipt_details->commission_agent_label))
					<br/>
					<strong>{{ $receipt_details->commission_agent_label }}</strong> {{ $receipt_details->commission_agent }}
				@endif
				@if(!empty($receipt_details->customer_rp_label))
					<br/>
					<strong>{{ $receipt_details->customer_rp_label }}</strong> {{ $receipt_details->customer_total_rp }}
				@endif
			</span>

			<span class="pull-right text-left">
				<b>{{$receipt_details->date_label}}</b> {{$receipt_details->invoice_date}}

				@if(!empty($receipt_details->due_date_label))
				<br><b>{{$receipt_details->due_date_label}}</b> {{$receipt_details->due_date ?? ''}}
				@endif

				@if(!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand))
					<br>
					@if(!empty($receipt_details->brand_label))
						<b>{!! $receipt_details->brand_label !!}</b>
					@endif
					{{$receipt_details->repair_brand}}
		        @endif


		        @if(!empty($receipt_details->device_label) || !empty($receipt_details->repair_device))
					<br>
					@if(!empty($receipt_details->device_label))
						<b>{!! $receipt_details->device_label !!}</b>
					@endif
					{{$receipt_details->repair_device}}
		        @endif

				@if(!empty($receipt_details->model_no_label) || !empty($receipt_details->repair_model_no))
					<br>
					@if(!empty($receipt_details->model_no_label))
						<b>{!! $receipt_details->model_no_label !!}</b>
					@endif
					{{$receipt_details->repair_model_no}}
		        @endif

				@if(!empty($receipt_details->serial_no_label) || !empty($receipt_details->repair_serial_no))
					<br>
					@if(!empty($receipt_details->serial_no_label))
						<b>{!! $receipt_details->serial_no_label !!}</b>
					@endif
					{{$receipt_details->repair_serial_no}}<br>
		        @endif
				@if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
					@if(!empty($receipt_details->repair_status_label))
						<b>{!! $receipt_details->repair_status_label !!}</b>
					@endif
					{{$receipt_details->repair_status}}<br>
		        @endif
		        
		        @if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
					@if(!empty($receipt_details->repair_warranty_label))
						<b>{!! $receipt_details->repair_warranty_label !!}</b>
					@endif
					{{$receipt_details->repair_warranty}}
					<br>
		        @endif
		        
		        @if(!empty($receipt_details->shipping_custom_field_1_label))
					<br><strong>{!!$receipt_details->shipping_custom_field_1_label!!} :</strong> {!!$receipt_details->shipping_custom_field_1_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->shipping_custom_field_2_label))
					<br><strong>{!!$receipt_details->shipping_custom_field_2_label!!}:</strong> {!!$receipt_details->shipping_custom_field_2_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->shipping_custom_field_3_label))
					<br><strong>{!!$receipt_details->shipping_custom_field_3_label!!}:</strong> {!!$receipt_details->shipping_custom_field_3_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->shipping_custom_field_4_label))
					<br><strong>{!!$receipt_details->shipping_custom_field_4_label!!}:</strong> {!!$receipt_details->shipping_custom_field_4_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->shipping_custom_field_5_label))
					<br><strong>{!!$receipt_details->shipping_custom_field_2_label!!}:</strong> {!!$receipt_details->shipping_custom_field_5_value ?? ''!!}
				@endif
				{{-- sale order --}}
				@if(!empty($receipt_details->sale_orders_invoice_no))
					<br>
					<strong>@lang('restaurant.order_no'):</strong> {!!$receipt_details->sale_orders_invoice_no ?? ''!!}
				@endif

				@if(!empty($receipt_details->sale_orders_invoice_date))
					<br>
					<strong>@lang('lang_v1.order_dates'):</strong> {!!$receipt_details->sale_orders_invoice_date ?? ''!!}
				@endif

				@if(!empty($receipt_details->sell_custom_field_1_value))
					<br>
					<strong>{{ $receipt_details->sell_custom_field_1_label }}:</strong> {!!$receipt_details->sell_custom_field_1_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->sell_custom_field_2_value))
					<br>
					<strong>{{ $receipt_details->sell_custom_field_2_label }}:</strong> {!!$receipt_details->sell_custom_field_2_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->sell_custom_field_3_value))
					<br>
					<strong>{{ $receipt_details->sell_custom_field_3_label }}:</strong> {!!$receipt_details->sell_custom_field_3_value ?? ''!!}
				@endif

				@if(!empty($receipt_details->sell_custom_field_4_value))
					<br>
					<strong>{{ $receipt_details->sell_custom_field_4_label }}:</strong> {!!$receipt_details->sell_custom_field_4_value ?? ''!!}
				@endif

			</span>
		</p>
	</div>
</div>

<div class="row" style="color: #000000 !important;">
	@includeIf('sale_pos.receipts.partial.common_repair_invoice')
</div>

<div class="row receipt-reference-items" style="color: #000000 !important;">
	<div class="col-xs-12">
		<br/>
		<table class="table table-responsive table-slim receipt-items-table">
			<thead>
				<tr>
					<th class="receipt-item-product">Name</th>
					<th class="text-right receipt-item-qty">Qty</th>
					<th class="text-right receipt-item-subtotal">Price</th>
				</tr>
			</thead>
			<tbody>
				@forelse($receipt_details->lines as $line)
					<tr>
						<td class="receipt-item-product">
							@if(!empty($line['image']))
								<img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
							@endif
                            {{$line['name']}} {{$line['product_variation']}} {{$line['variation']}} 
                            @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
                            @if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
                            @if(!empty($line['product_description']))
                            	<small>
                            		{!!$line['product_description']!!}
                            	</small>
                            @endif 
                            @if(!empty($line['sell_line_note']))
                            <br>
                            <small>
                            	{!!$line['sell_line_note']!!}
                            </small>
                            @endif 
                            @if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
                            @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif

                            @if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
                            @if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif

                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br>
                            	{{$line['base_unit_price']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
                            </small>
                            @endif
                        </td>
						<td class="text-right receipt-item-qty" data-label="Qty">
							{{$line['quantity']}} {{$line['units']}} 

							@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}}
                            </small>
                            @endif
						</td>
						<td class="text-right receipt-item-subtotal" data-label="Price">{{$line['line_total']}}</td>
					</tr>
					@if(!empty($line['modifiers']))
						@foreach($line['modifiers'] as $modifier)
							<tr>
								<td class="receipt-item-product">
		                            {{$modifier['name']}} {{$modifier['variation']}} 
		                            @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
		                            @if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif 
		                        </td>
								<td class="text-right receipt-item-qty" data-label="Qty">{{$modifier['quantity']}} {{$modifier['units']}} </td>
								<td class="text-right receipt-item-subtotal" data-label="Price">{{$modifier['line_total']}}</td>
							</tr>
						@endforeach
					@endif
				@empty
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>

<div class="row receipt-reference-summary" style="color: #000000 !important;">
	<div class="col-md-12"><hr/></div>
	<div class="col-xs-12">
        <div class="table-responsive">
          	<table class="table table-slim receipt-totals-table">
				<tbody>
					<tr>
						<th>
							{!! $receipt_details->subtotal_label !!}
						</th>
						<td class="text-right">
							{{$receipt_details->subtotal}}
						</td>
					</tr>
					@if(!empty($receipt_details->total_exempt_uf))
					<tr>
						<th style="width:70%">
							@lang('lang_v1.exempt')
						</th>
						<td class="text-right">
							{{$receipt_details->total_exempt}}
						</td>
					</tr>
					@endif
					<!-- Shipping Charges -->
					@if(!empty($receipt_details->shipping_charges))
						<tr>
							<th>
								{!! $receipt_details->shipping_charges_label !!}
							</th>
							<td class="text-right">
								{{$receipt_details->shipping_charges}}
							</td>
						</tr>
					@endif

					@if(!empty($receipt_details->packing_charge))
						<tr>
							<th>
								{!! $receipt_details->packing_charge_label !!}
							</th>
							<td class="text-right">
								{{$receipt_details->packing_charge}}
							</td>
						</tr>
					@endif

					<!-- Discount -->
					@if( !empty($receipt_details->discount) )
						<tr>
							<th>
								{!! $receipt_details->discount_label !!}
							</th>

							<td class="text-right">
								(-) {{$receipt_details->discount}}
							</td>
						</tr>
					@endif

					@if( !empty($receipt_details->total_line_discount) )
						<tr>
							<th>
								{!! $receipt_details->line_discount_label !!}
							</th>

							<td class="text-right">
								(-) {{$receipt_details->total_line_discount}}
							</td>
						</tr>
					@endif

					@if( !empty($receipt_details->additional_expenses) )
						@foreach($receipt_details->additional_expenses as $key => $val)
							<tr>
								<td>
									{{$key}}:
								</td>

								<td class="text-right">
									(+) {{$val}}
								</td>
							</tr>
						@endforeach
					@endif

					@if( !empty($receipt_details->reward_point_label) )
						<tr>
							<th>
								{!! $receipt_details->reward_point_label !!}
							</th>

							<td class="text-right">
								(-) {{$receipt_details->reward_point_amount}}
							</td>
						</tr>
					@endif

					<!-- Tax -->
					@if( !empty($receipt_details->tax) )
						<tr>
							<th>
								{!! $receipt_details->tax_label !!}
							</th>
							<td class="text-right">
								(+) {{$receipt_details->tax}}
							</td>
						</tr>
					@endif

					@if( $receipt_details->round_off_amount > 0)
						<tr>
							<th>
								{!! $receipt_details->round_off_label !!}
							</th>
							<td class="text-right">
								{{$receipt_details->round_off}}
							</td>
						</tr>
					@endif

					<!-- Total -->
					<tr>
						<th>
							{!! $receipt_details->total_label !!}
						</th>
						<td class="text-right">
							{{$receipt_details->total}}
							@if(!empty($receipt_details->total_in_words))
								<br>
								<small>({{$receipt_details->total_in_words}})</small>
							@endif
						</td>
					</tr>
					@if(!empty($receipt_details->amount_received))
						<tr>
							<th>
								Amount Received:
							</th>
							<td class="text-right">
								{{$receipt_details->amount_received}}
							</td>
						</tr>
					@endif

					@if(!empty($receipt_details->change_return))
						<tr>
							<th>
								Change:
							</th>
							<td class="text-right">
								{{$receipt_details->change_return}}
							</td>
						</tr>
					@elseif(!empty($receipt_details->total_due))
						<tr>
							<th>
								Balance:
							</th>
							<td class="text-right">
								{{$receipt_details->total_due}}
							</td>
						</tr>
					@endif
					@if(!empty($receipt_details->total_previous_due))
						<tr>
							<th>
								{!! $receipt_details->total_previous_due_label !!}
							</th>
							<td class="text-right">
								{{$receipt_details->total_previous_due}}
							</td>
						</tr>
					@endif
					@if(!empty($receipt_details->all_due))
						<tr>
							<th>
								{!! $receipt_details->all_bal_label !!}
							</th>
							<td class="text-right">
								{{$receipt_details->all_due}}
							</td>
						</tr>
					@endif
				</tbody>
        	</table>
        </div>
    </div>

    <div class="border-bottom col-md-12">
	    @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
	        <!-- tax -->
	        @if(!empty($receipt_details->taxes))
	        	<table class="table table-slim table-bordered">
	        		<tr>
	        			<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
	        		</tr>
	        		@foreach($receipt_details->taxes as $key => $val)
	        			<tr>
	        				<td class="text-center"><b>{{$key}}</b></td>
	        				<td class="text-center">{{$val}}</td>
	        			</tr>
	        		@endforeach
	        	</table>
	        @endif
	    @endif
	</div>

	@if(!empty($receipt_details->additional_notes))
	    <div class="col-xs-12">
	    	<p>{!! nl2br($receipt_details->additional_notes) !!}</p>
	    </div>
    @endif
    
</div>
<div class="row receipt-reference-footer" style="color: #000000 !important;">
	<div class="col-xs-12 receipt-barcode-block">
		<img class="center-block receipt-barcode" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
		<div class="receipt-barcode-text">{{$receipt_details->invoice_no}}</div>
	</div>

	<div class="col-xs-12 receipt-closing-message">
		@if(!empty($receipt_details->footer_text))
			{!! $receipt_details->footer_text !!}
		@else
			<p>Thank you for shopping with us!<br>Please come again.</p>
		@endif

	</div>
	@if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
		<div class="col-xs-12 text-center receipt-qr-block">
			<img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE', 3, 3, [39, 48, 54])}}">
		</div>
	@endif
</div>

<style>
@media print {
	body {
		color: #000 !important;
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: 10px !important;
	}

	.receipt-reference-header .row,
	.receipt-reference-items .row,
	.receipt-reference-summary .row,
	.receipt-reference-footer .row {
		margin-left: 0;
		margin-right: 0;
	}

	.receipt-reference-header,
	.receipt-reference-items,
	.receipt-reference-summary,
	.receipt-reference-footer {
		max-width: 100%;
		margin-left: 0 !important;
		margin-right: 0 !important;
	}

	.receipt-reference-header [class*="col-"],
	.receipt-reference-items [class*="col-"],
	.receipt-reference-summary [class*="col-"],
	.receipt-reference-footer [class*="col-"] {
		padding-left: 0;
		padding-right: 0;
	}

	.receipt-reference-header .img {
		max-height: 58px !important;
		margin: 5px auto 8px;
	}

	.receipt-reference-header h2 {
		margin: 0 0 6px;
		font-size: 15px;
		font-weight: 700;
	}

	.receipt-reference-header p {
		margin: 0 0 5px;
		line-height: 1.35;
	}

	.receipt-reference-title {
		margin: 9px 0;
		padding: 6px 0;
		border-top: 1px dashed #222;
		border-bottom: 1px dashed #222;
		font-size: 12px;
		font-weight: 700;
		letter-spacing: .4px;
		text-transform: uppercase;
	}

	.receipt-reference-meta {
		padding: 2px 0 7px !important;
		border-bottom: 1px dashed #222;
		font-size: 10px;
		line-height: 1.45;
	}

	.receipt-reference-meta > p > .pull-left,
	.receipt-reference-meta > p > .pull-right {
		display: block;
		float: none !important;
		max-width: 100%;
		text-align: left !important;
	}

	.receipt-reference-items br {
		line-height: 1.3;
	}

	.receipt-reference-items .table {
		margin: 9px 0 6px;
		border-bottom: 1px dashed #222;
		font-size: 10px;
		table-layout: fixed;
		width: 100%;
	}

	.receipt-reference-items .table > thead > tr > th,
	.receipt-reference-items .table > tbody > tr > td {
		padding: 4px 1px;
		border-top: 0;
		overflow-wrap: normal;
		word-break: normal;
	}

	.receipt-reference-items .table > thead > tr > th {
		border-bottom: 1px dashed #222;
		font-size: 9px;
		text-transform: uppercase;
	}

	.receipt-reference-items .receipt-item-product {
		width: 58%;
	}

	.receipt-reference-items .receipt-item-qty {
		width: 14%;
	}

	.receipt-reference-items .receipt-item-subtotal {
		width: 28%;
	}

	.receipt-reference-items .table > thead > tr > th.text-right,
	.receipt-reference-items .table > tbody > tr > td.text-right {
		white-space: nowrap;
	}

	.receipt-reference-items .receipt-item-product {
		overflow-wrap: anywhere;
		word-break: normal;
	}

	.receipt-reference-items .receipt-item-qty,
	.receipt-reference-items .receipt-item-subtotal {
		font-size: 9px;
	}

	.receipt-reference-summary > .col-md-12 > hr {
		margin: 5px 0;
		border-top: 1px dashed #222;
	}

	.receipt-reference-summary .table {
		margin-bottom: 4px;
		font-size: 10px;
		width: 100%;
	}

	.receipt-reference-summary .table > tbody > tr > th,
	.receipt-reference-summary .table > tbody > tr > td {
		padding: 2px 1px;
		border-top: 0;
		overflow-wrap: normal;
		word-break: normal;
	}

	.receipt-reference-summary .table > tbody > tr > th {
		width: 45%;
	}

	.receipt-reference-summary .table > tbody > tr > td {
		width: 55%;
		white-space: nowrap;
	}

	.receipt-reference-summary .col-xs-6 {
		width: 100%;
	}

	.receipt-reference-summary .col-xs-6:last-of-type tr:last-child th,
	.receipt-reference-summary .col-xs-6:last-of-type tr:last-child td {
		padding-top: 5px;
		border-top: 1px dashed #222;
		font-size: 11px;
	}

	.receipt-reference-footer {
		margin-top: 12px !important;
		padding-top: 10px;
		border-top: 1px dashed #222;
		text-align: center;
		line-height: 1.45;
	}

	.receipt-barcode-block {
		margin: 6px 0 12px;
		text-align: center;
	}

	.receipt-barcode {
		max-width: 72%;
		height: 42px;
		margin: 0 auto;
	}

	.receipt-barcode-text {
		margin-top: 2px;
		font-size: 8px;
	}

	.receipt-qr-block {
		margin-top: 8px;
	}

	.receipt-closing-message p {
		margin: 0 0 7px;
	}

	.receipt-closing-message .receipt-closing-details {
		margin-bottom: 2px;
		font-size: 9px;
	}
}

@media print and (max-width: 90mm) {
	body {
		font-size: 9px !important;
	}

	.receipt-reference-items .table {
		font-size: 9px;
	}

	.receipt-reference-items .table > thead > tr > th,
	.receipt-reference-items .table > tbody > tr > td {
		padding: 3px 1px;
	}

	.receipt-reference-items .table > thead > tr > th {
		font-size: 8px;
	}

	.receipt-reference-items .receipt-item-product {
		width: 54%;
	}

	.receipt-reference-items .receipt-item-qty {
		width: 16%;
	}

	.receipt-reference-items .receipt-item-subtotal {
		width: 30%;
	}

	.receipt-reference-summary .table {
		font-size: 9px;
	}

	.receipt-reference-summary .table > tbody > tr > th,
	.receipt-reference-summary .table > tbody > tr > td {
		padding: 2px 0;
	}

	.receipt-barcode {
		max-width: 80%;
		height: 38px;
	}
}
</style>
