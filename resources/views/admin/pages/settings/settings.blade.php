@extends('admin.layout.default')

@section('settings', 'active menu-item-open')

@section('content')
    <div class="card card-custom">
        <div class="row no-gutters">
            <!-- Settings Sidebar -->
            <div class="col-md-3 pr-0">
                <div class="settings-sidebar h-100 d-flex flex-column justify-content-between">
                    <div>

                        <div class="sidebar-menu flex-grow-1">
                            <nav class="nav flex-column nav-sidebar">
                                <a href="#general" class="nav-link active" data-toggle="pill" role="tab">
                                    <i class="fas fa-cog mr-2"></i> Basic Information
                                </a>
                               {{-- bank details --}}
                               <a href="#bank" class="nav-link" data-toggle="pill" role="tab">
                                   <i class="fas fa-university mr-2"></i> Bank Details
                               </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="col-md-9 pl-0">
                <div class="card card-custom h-100">
                    <div class="card-body">
                        <div class="tab-content" id="settingsTabContent">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="settings-header mb-4">
                                    <h4>General Settings</h4>
                                    <p class="text-muted">Configure basic company information and system settings</p>
                                </div>
                                <form method="POST" action="" class="w-100">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        @php
                                            $fields = [
                                                ['company_name', 'Company Name', 'text', 'required' => true],
                                                ['website_url', 'Website URL', 'url', 'required' => true],
                                                ['registered_office_address', 'Registered Office Address', 'textarea', 'required' => true],
                                                ['office_address', 'Office Address', 'textarea', 'required' => true],
                                                ['phone_number', 'Phone Number', 'text', 'pattern' => '[0-9]{10}', 'maxlength' => 10, 'required' => true],
                                                ['email_id', 'Email Address', 'email', 'required' => true],
                                                ['whatsapp', 'WhatsApp Number', 'text', 'required' => true],
                                                ['customer_care', 'Customer Care', 'text', 'required' => true],
                                                ['gst_number', 'GST Number', 'text', 'required' => false],
                                                ['pan_number', 'PAN Number', 'text', 'required' => false],
                                            ];
                                        @endphp
                                        @foreach ($fields as $field)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">{{ $field[1] }}</label>
                                                    @if ($field[2] === 'textarea')
                                                        <textarea name="{{ $field[0] }}" class="form-control" rows="3" placeholder="Enter {{ $field[1] }}" @if(isset($field['required']) && $field['required']) isrequired="required" @endif>{{ $details->{$field[0]} ?? '' }}</textarea>
                                                    @else
                                                        <input type="{{ $field[2] }}" name="{{ $field[0] }}"
                                                            value="{{ $details->{$field[0]} ?? '' }}" class="form-control @if ($field[0] === 'phone_number') number @endif" @if(isset($field['required']) && $field['required']) isrequired="required" @endif
                                                            placeholder="Enter {{ $field[1] }}"
                                                            @if ($field[0] === 'phone_number') pattern="[0-9]{10}" class="number" maxlength="10" @endif>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Time Zone</label>
                                                <select name="timezone" class="form-control" isrequired="isrequired">
                                                    <option value="">Select Timezone</option>
                                                    <option value="Asia/Kolkata"
                                                        {{ ($details->timezone ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>
                                                        Asia/Kolkata (IST)</option>
                                                    <option value="UTC"
                                                        {{ ($details->timezone ?? '') == 'UTC' ? 'selected' : '' }}>UTC
                                                    </option>
                                                    <option value="America/New_York"
                                                        {{ ($details->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>
                                                        America/New_York (EST)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Currency</label>
                                                <select name="currency" class="form-control" isrequired="isrequired">
                                                    <option value="">Select Currency</option>
                                                    <option value="INR"
                                                        {{ ($details->currency ?? '') == 'INR' ? 'selected' : '' }}>INR (₹)
                                                    </option>
                                                    <option value="USD"
                                                        {{ ($details->currency ?? '') == 'USD' ? 'selected' : '' }}>USD ($)
                                                    </option>
                                                    <option value="EUR"
                                                        {{ ($details->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="setting_id" value="{{ $details->id ?? '' }}">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-2"></i>Update Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Bank Details -->
                            <div class="tab-pane fade" id="bank" role="tabpanel">
                                <div class="settings-header mb-4">
                                    <h4>Bank Details</h4>
                                    <p class="text-muted">Configure your bank account details for transactions</p>
                                </div>
                                <form method="POST" action="{{url('admin/settings/bank')}}" class="w-100">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        @php
                                            $bankFields = [
                                                ['bank_name', 'Bank Name', 'text', 'required' => true],
                                                ['account_number', 'Account Number', 'text', 'required' => true],
                                                ['ifsc_code', 'IFSC Code', 'text', 'required' => true],
                                                ['account_holder_name', 'Account Holder Name', 'text', 'required' => true],
                                                ['branch_name', 'Branch Name', 'text', 'required' => true],
                                                ['bank_address', 'Bank Address', 'textarea', 'required' => true],
                                            ];
                                        @endphp
                                        @foreach ($bankFields as $field)
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label class="font-weight-bold">{{ $field[1] }}</label>
                                                    @if ($field[2] === 'textarea')
                                                        <textarea name="{{ $field[0] }}" class="form-control" rows="3" placeholder="Enter {{ $field[1] }}" @if($field['required']) isrequired="isrequired" @endif>{{ $bank_details->{$field[0]} ?? '' }}</textarea>
                                                    @else
                                                        <input type="{{ $field[2] }}" name="{{ $field[0] }}"
                                                            value="{{ $bank_details->{$field[0]} ?? '' }}" class="form-control" @if($field['required']) isrequired="isrequired" @endif
                                                            placeholder="Enter {{ $field[1] }}">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="bank_id" value="{{ $bank_details->id ?? '' }}">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-2"></i>Update Bank Details
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .settings-sidebar {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar-header {
            background: linear-gradient(45deg, #007bff, #0056b3) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu {
            padding: 0;
        }
        .nav-sidebar .nav-link {
            padding: 15px 20px;
            color: #495057;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 0;
            display: flex;
            align-items: center;
        }
        .nav-sidebar .nav-link:hover {
            background: #f8f9fa;
            color: #007bff;
            text-decoration: none;
        }
        .nav-sidebar .nav-link.active {
            background: #0e4c83;
            color: white;
            position: relative;
        }
        .nav-sidebar .nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #ffc107;
            border-radius: 2px 0 0 2px;
        }
        .nav-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        .settings-header {
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 15px;
        }
        .settings-header h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .form-group label {
            color: #495057;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }
        .btn-primary {
            background: linear-gradient(45deg, #0e4c83, #0e4c83);
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        .card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }
        .tab-content {
            min-height: 500px;
        }
        @media (max-width: 768px) {
            .settings-sidebar {
                margin-bottom: 20px;
            }
            .nav-sidebar .nav-link {
                padding: 12px 15px;
                font-size: 14px;
            }
            .nav-sidebar .nav-link i {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Sidebar navigation
            $('.nav-sidebar .nav-link').on('click', function(e) {
                e.preventDefault();
                $('.nav-sidebar .nav-link').removeClass('active');
                $(this).addClass('active');
                $('.tab-pane').removeClass('show active');
                $($(this).attr('href')).addClass('show active');
            });
        });
    </script>
@endsection
