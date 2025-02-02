@php use Illuminate\Support\Str; @endphp
@extends('layouts.back-end.app')

@section('title', 'Prime Member List')

@section('content')

<?php 

// echo "<pre>";
// print_r($primemembers);

?>

    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/customer.png')}}" alt="">
                {{'Prime Member List'}}
                <span class="badge badge-soft-dark radius-50">{{count($primemembers)}}</span>
            </h2>
        </div>
        <div class="card">
            <div class="px-3 py-4">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{translate('search_by_Name_or_Email_or_Phone')}}"
                                       aria-label="Search orders" value="{{ request('searchValue') }}">
                                <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                        <div class="d-flex justify-content-sm-end">
                            <button type="button" style="display:none" class="btn btn-outline--primary" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a class="dropdown-item"
                                       href="{{route('admin.customer.export',['searchValue'=>request('searchValue')])}}">
                                        <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" alt="">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive datatable-custom">
                <table
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{'Id'}}</th>
                        
                        <th>{{translate('customer_name')}}</th>
                        <th>{{translate('contact_info')}}</th>
                        <th>{{'Prime Member'}}</th>
                        <th>{{'Payment Status'}} </th>
                        <th>{{'Amount'}} </th>
                        <th>{{'Subscription Type'}} </th>
                        <th>{{'Tranasction id '}} </th>
                        <th>{{'Created At'}} </th>
                        <th>{{'Expire Date'}} </th>
                       

                     
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($primemembers as $key=>$primemember)
                        
 @php
                            // Query user based on customer_id
                            $user = \App\Models\User::find($primemember->customer_id);
                        @endphp


                        <tr>

                            <td>
                                {{$primemembers->firstItem()+$key}}
                            </td>
                          <td> {{$primemember->id}}</td>


                            <td>
                                <a href="{{route('admin.customer.view',[$primemember['id']])}}"
                                   class="title-color hover-c1 d-flex align-items-center gap-10">
                                    {{$user->name ?? ''}}
                                </a>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <strong><a class="title-color hover-c1"
                                               href="mailto:{{$user->email ?? ''}}">{{$user->email ?? ''}}</a></strong>

                                </div>
                                <a class="title-color hover-c1" href="tel:{{$user->phone ?? ''}}">{{$user->phone ?? ''}}</a>

                            </td>
                            <td>


{{ optional($user)->prime_member_status == 0 ? 'Expire' : 'Active' }}
                            </td>
                            <td>
                                <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                    {{$primemember->payment_status=="paid"?"Paid":""}}
                                </label>
                            </td>
                           
                                <td>
                            
                               {{$primemember->amount}}
                            </td> <td>
                         

                            @if ($primemember->duration == 1)
    <p> Monthly</p>
@elseif ($primemember->duration == 2)
    <p>6 Months</p>
@elseif ($primemember->duration == 3)
    <p>Yearly</p>
@else
    
@endif

                               
                            </td> <td>
                            {{$primemember->transaction_ref}}
                               
                            </td>
                            <td>
                            {{$primemember->created_at}}
                               
                            </td>

                            <td>
                          
{{ $primemember->expiry_date->format('Y-m-d') }}


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!! $primemembers->links() !!}
                </div>
            </div>
            @if(count($primemembers)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg')}}"
                         alt="Image Description">
                    <p class="mb-0">{{translate('no_data_to_show')}}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
