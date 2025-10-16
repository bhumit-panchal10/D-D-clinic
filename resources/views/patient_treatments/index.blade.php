@extends('layouts.app')
@section('title', 'Patient Treatments')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .teeth_wrapper {
            width: 55px;
        }

        .teeth_wrapper img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: optimizeQuality;
            transition: filter 0.2s ease;
        }

        /* Treatment done (green image) */
        .tooth-green {
            filter: drop-shadow(0 0 1px #15803d);
            /* green outline */
        }

        /* Diagnosis (yellow image) */
        .tooth-yellow {
            filter: drop-shadow(0 0 1px #ca8a04);
            /* yellow outline */
        }

        /* Neutral/other teeth – turn PNG into light gray */
        .tooth-neutral {
            filter: grayscale(100%) brightness(1.5) contrast(1.2) drop-shadow(0 0 1px #6b7280);
            opacity: 0.9;
        }

        .dx-card {
            border-radius: 14px;
            overflow: hidden
        }

        .dx-head .dx-title {
            font-weight: 700;
            letter-spacing: .2px
        }

        .dx-meta {
            color: #64748b;
            font-size: .9rem
        }

        .dx-list .list-group-item {
            border: 0;
            border-bottom: 1px solid #eef0f3;
            padding: .8rem 1rem
        }

        .dx-list .list-group-item:last-child {
            border-bottom: 0
        }

        .dx-pill {
            font-size: .85rem;
            padding: .35rem .6rem;
            border-radius: 20px;
            background: #f1f5ff;
            color: #1f6bff
        }

        .dx-chip {
            background: #f8fafc;
            border: 1px solid #eef0f3;
            border-radius: 12px;
            padding: .35rem .6rem;
            font-size: .9rem
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile No 1: {{ $patient->mobile1 }}
                        @if ($patient->mobile2 != '')
                            | Mobile No 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="card">
                    <div class="row">
                        <div class="col-lg-7">
                            <section>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 "
                                            style="border-right : 1px solid grey; padding: 20px;">
                                            <div class="heading mb-3">Upper Right(1)</div>
                                            <div class="adult-teeth-group">
                                                <div class="row d-flex justify-content-between p-2">
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/18.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/18.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/18.png') }}"
                                                                alt="18">
                                                            <p>18</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/17.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/17.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/17.png') }}"
                                                                alt="17">
                                                            <p>17</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/16.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/16.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/16.png') }}"
                                                                alt="16">
                                                            <p>16</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/15.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/15.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/15.png') }}"
                                                                alt="15">
                                                            <p>15</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/14.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/14.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/14.png') }}"
                                                                alt="14">
                                                            <p>14</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/13.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/13.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/13.png') }}"
                                                                alt="13">
                                                            <p>13</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/12.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/12.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/12.png') }}"
                                                                alt="12">
                                                            <p>12</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/11.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/11.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/11.png') }}"
                                                                alt="11">
                                                            <p>11</p>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="children-teeth-group" style="display: none;">
                                                    <div class="row d-flex justify-content-between p-2">

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper px-0">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/1E.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/1E.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/1E.png') }}"
                                                                    alt="55">
                                                                <p>55</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/1D.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/1D.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/1D.png') }}"
                                                                    alt="54">
                                                                <p>54</p>
                                                            </div>

                                                        </div>

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/1C.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/1C.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/1C.png') }}"
                                                                    alt="53">
                                                                <p>53</p>
                                                            </div>

                                                        </div>

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/1B.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/1B.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/1B.png') }}"
                                                                    alt="52">
                                                                <p>52</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper adult-teeth">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/1A.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/1A.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/1A.png') }}"
                                                                    alt="51">
                                                                <p>51</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6" style="padding: 20px;">
                                            <div class="heading mb-3">Upper Left(2)</div>

                                            <div class="adult-teeth-group">
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper adult-teeth">
                                                            <img src="{{ asset('assets/images/TeethYellow/21.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/21.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/21.png') }}"
                                                                alt="21">
                                                            <p>21</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/22.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/22.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/22.png') }}"
                                                                alt="22">
                                                            <p>22</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/23.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/23.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/23.png') }}"
                                                                alt="23">
                                                            <p>23</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/24.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/24.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/24.png') }}"
                                                                alt="24">
                                                            <p>24</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/25.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/25.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/25.png') }}"
                                                                alt="25">
                                                            <p>25</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/26.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/26.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/26.png') }}"
                                                                alt="26">
                                                            <p>26</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/27.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/27.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/27.png') }}"
                                                                alt="27">
                                                            <p>27</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/28.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/28.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/28.png') }}"
                                                                alt="28">
                                                            <p>28</p>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="children-teeth-group" style="display: none;">
                                                    <div class="row d-flex justify-content-between p-2">
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper adult-teeth">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/2A.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/2A.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/2A.png') }}"
                                                                    alt="61">
                                                                <p>61</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/2B.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/2B.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/2B.png') }}"
                                                                    alt="62">
                                                                <p>62</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/2C.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/2C.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/2C.png') }}"
                                                                    alt="63">
                                                                <p>63</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/2D.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/2D.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/2D.png') }}"
                                                                    alt="64">
                                                                <p>64</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/2E.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/2E.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/2E.png') }}"
                                                                    alt="65">
                                                                <p>65</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" style="border-top : 3px solid black;">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6"
                                            style="border-right : 1px solid grey; padding: 20px;">
                                            <div class="heading mb-3">lower Right(4)</div>
                                            <div class="adult-teeth-group">
                                                <div class="row d-flex justify-content-between p-2">

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/48.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/48.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/48.png') }}"
                                                                alt="48">
                                                            <p>48</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/47.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/47.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/47.png') }}"
                                                                alt="47">
                                                            <p>47</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/46.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/46.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/46.png') }}"
                                                                alt="46">
                                                            <p>46</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/45.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/45.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/45.png') }}"
                                                                alt="45">
                                                            <p>45</p>
                                                        </div>

                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/44.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/44.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/44.png') }}"
                                                                alt="44">
                                                            <p>44</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/43.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/43.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/43.png') }}"
                                                                alt="43">
                                                            <p>43</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/42.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/42.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/42.png') }}"
                                                                alt="42">
                                                            <p>42</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/41.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/41.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/41.png') }}"
                                                                alt="41">
                                                            <p>41</p>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="children-teeth-group" style="display: none;">
                                                    <div class="row d-flex justify-content-between p-2">

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/3E.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/3E.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/3E.png') }}"
                                                                    alt="75">
                                                                <p>75</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/4D.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/4D.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/4D.png') }}"
                                                                    alt="74">
                                                                <p>74</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/4C.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/4C.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/4C.png') }}"
                                                                    alt="73">
                                                                <p>73</p>
                                                            </div>

                                                        </div>

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/4B.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/4B.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/4B.png') }}"
                                                                    alt="72">
                                                                <p>72</p>
                                                            </div>

                                                        </div>

                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper adult-teeth">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/4A.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/4A.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/4A.png') }}"
                                                                    alt="71">
                                                                <p>71</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6" style="padding: 20px;">
                                            <div class="heading mb-3">Lower Left(3)</div>
                                            <div class="adult-teeth-group">
                                                <div class="row d-flex justify-content-between">
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/31.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/31.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/31.png') }}"
                                                                alt="31">
                                                            <p>31</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/32.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/32.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/32.png') }}"
                                                                alt="32">
                                                            <p>32</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/33.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/33.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/33.png') }}"
                                                                alt="33">
                                                            <p>33</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/34.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/34.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/34.png') }}"
                                                                alt="34">
                                                            <p>34</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/35.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/35.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/35.png') }}"
                                                                alt="35">
                                                            <p>35</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/36.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/36.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/36.png') }}"
                                                                alt="36">
                                                            <p>36</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/37.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/37.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/37.png') }}"
                                                                alt="37">
                                                            <p>37</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/TeethYellow/38.png') }}"
                                                                data-color="{{ asset('assets/images/TeethGreen/38.png') }}"
                                                                data-bw="{{ asset('assets/images/TeethYellow/38.png') }}"
                                                                alt="38">
                                                            <p>38</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="children-teeth-group" style="display: none;">
                                                    <div class="row d-flex justify-content-between p-2">
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper adult-teeth">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/3A.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/3A.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/3A.png') }}"
                                                                    alt="81">
                                                                <p>81</p>
                                                            </div>



                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/3B.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/3B.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/3B.png') }}"
                                                                    alt="82">
                                                                <p>82</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/3C.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/3C.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/3C.png') }}"
                                                                    alt="83">
                                                                <p>83</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/3D.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/3D.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/3D.png') }}"
                                                                    alt="84">
                                                                <p>84</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-1 col-md-3 col-sm-3 col-3 px-0">
                                                            <div class="teeth_wrapper">
                                                                <img src="{{ asset('assets/images/ChildYellowTeeth/3E.png') }}"
                                                                    data-color="{{ asset('assets/images/ChildGreenTeeth/3E.png') }}"
                                                                    data-bw="{{ asset('assets/images/ChildYellowTeeth/3E.png') }}"
                                                                    alt="85">
                                                                <p>85</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>

                                <span id ="icon-adult" class="ms-3 fs-3"><i class="fa fa-user"></i></span>

                                <span id ="icon-children" class="ms-3 fs-3"><i class="fa fa-child"></i></span>

                                <form action="{{ route('patient_treatments.search', $patient->id) }}" method="GET"
                                    id="toothSearchForm" class="mb-3 justify-content-end d-flex">
                                    <input type="hidden" name="tooth_selection" maxlength="50"
                                        id="tooth_selection_search"
                                        value="{{ old('tooth_selection', $toothSelection ?? '') }}" class="form-control"
                                        placeholder="E.g., 12, 14">

                                    <button type="submit" class="btn btn-primary">Search</button>&nbsp;&nbsp;
                                    <a href="{{ route('patient_treatments.index', $patient->id) }}"
                                        class="btn btn-primary">Reset</a>
                                </form>
                            </section>
                        </div>
                        <div class="col-lg-5">
                            <div class="col-lg-12 mt-2" id="treatment-form">
                                <div class="card-body">
                                    <form action="{{ route('patient_treatments.store', $patient->id) }}" method="POST">
                                        @csrf

                                        <div class="row">

                                            <div class="col-md-6">
                                                <label for="treatment_id" class="form-label">Diagnosis Type<span
                                                        class="text-danger">*</span></label>
                                                <select name="diagnosis_type" id="diagnosis_type" class="form-control"
                                                    required>
                                                    <option value="" disabled selected>Select Diagnosis</option>
                                                    <option value="1">General</option>
                                                    <option value="2">Local</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="Diagnosis" class="form-label">Diagnosis <span
                                                        class="text-danger">*</span></label>
                                                <select name="diagnosis_id[]" id="diagnosis_list" class="form-control"
                                                    multiple required>
                                                    <option value="" disabled>Select Diagnosis</option>
                                                    {{-- Options will be loaded dynamically via JS --}}
                                                </select>
                                            </div>


                                            <div class="col-md-6  mt-3">
                                                <label for="tooth_selection" class="form-label">Tooth Selection</label>
                                                <input type="text" name="tooth_selection" maxlength="50"
                                                    id="tooth_selection" class="form-control" placeholder="E.g., 12, 14"
                                                    value="{{ old('tooth_selection', $toothSelection ?? '') }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="Comment" class="form-label">Comment</label>
                                                <input type="text" name="comment" id="comment"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" name="date" id="date"
                                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                    class="form-control">
                                            </div>

                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="reset" class="btn btn-primary">Clear</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center mb-3 flex">
                        <h2>Diagnosis List</h2>
                        <div class="d-flex justify-content-center align-items-center mb-3 gap-3">
                            {{-- <h2 class="mb-0">Diagnosis List</h2> --}}
                            <span
                                style="display:inline-block;width:16px;height:16px;
                 background:#ca8a04;border-radius:3px;margin-left:8px;"></span>
                            <small class="text-muted">Diagnosis (Pending)</small>
                        </div>

                    </div>

                    <div class="tab-content text-muted">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr no.</th>
                                                <th>Date</th>
                                                <th>Diagnosis</th>
                                                <th>Tooth Selected</th>
                                                <th>Notes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patientTreatments as $key => $treatment)
                                                <tr>
                                                    <td>{{ $patientTreatments->firstItem() + $key }}</td>
                                                    <td>
                                                        {{ $treatment->patientdocument && $treatment->patientdocument->date != '0000-00-00'
                                                            ? date('d-m-Y', strtotime($treatment->patientdocument->date))
                                                            : '' }}
                                                    </td>
                                                    <td>{{ $treatment->Diagnosis->Diagnosis_name ?? '' }}</td>
                                                    <td>{{ $treatment->tooth_selection }}</td>
                                                    <td>{{ $treatment->comment }}</td>

                                                    <td>
                                                        <a href="{{ route('document.multidocview', [$treatment->id]) }}"
                                                            class="btn btn-sm btn-primary" title="View Document">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <button class="btn btn-sm btn-primary upload-document-btn"
                                                            data-bs-toggle="modal" title="upload"
                                                            data-bs-target="#uploadDocumentModal"
                                                            onclick="getdatas(<?= $treatment->patient_id ?>,<?= $treatment->diagnosis_id ?>,<?= $treatment->id ?>)"
                                                            ;>
                                                            <i class="fas fa-upload"></i>
                                                        </button>

                                                        {{-- <button class="btn btn-sm btn-primary labwork-btn"
                                                            data-bs-toggle="modal" title="add"
                                                            data-bs-target="#labworkModal"
                                                            onclick="getid(<?= $treatment->patient_id ?>,<?= $treatment->diagnosis_id ?>,<?= $treatment->id ?>)"
                                                            ;>
                                                            Add Labwork
                                                        </button> --}}

                                                        <button class="btn btn-sm btn-primary delete-treatment"
                                                            data-id="{{ $treatment->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal">
                                                            Delete
                                                        </button>

                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#TreatmentModal"
                                                            data-patient-id="{{ $treatment->patient_id }}"
                                                            data-treatment-id="{{ $treatment->treatment_id }}"
                                                            data-diagnosis-id="{{ $treatment->Diagnosis->id ?? '' }}"
                                                            data-patient-treatment-id="{{ $treatment->id }}">
                                                            Treatment
                                                        </button>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $patientTreatments->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="text-center  flex">
                        <h2 class="mb-0">Treatment List</h2>
                    </div>

                    <div class="tab-content text-muted">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <form id="toQuoteForm" action="{{ route('quotation.index', $patient->id) }}"
                                        method="GET">
                                        <input type="hidden" name="prefill" value="1">

                                        @csrf

                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="submit" id="addToQuoteBtn" class="btn btn-primary btn-sm"
                                                disabled>
                                                Add to Quotation
                                            </button>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width:36px">
                                                        <input type="checkbox" id="checkAll">
                                                    </th>
                                                    <th>Sr no.</th>
                                                    <th>Diagnosis</th>
                                                    <th>Treatment</th>
                                                    <th>Tooth Selected</th>
                                                    <th>Notes</th>
                                                    <th>Amount</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($diagnoses as $key => $diag)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="treatment-check" name="items[]"
                                                                value="{{ $diag->id }}"> {{-- <- PatientTreatmentItem.id --}}
                                                        </td>
                                                        <td>{{ $diagnoses->firstItem() + $key }}</td>
                                                        <td>{{ $diag->DiagnosisMaster->Diagnosis_name ?? '' }}</td>
                                                        <td>{{ $diag->treatment->treatment_name ?? '' }}</td>
                                                        <td>{{ $diag->Diagnosis->tooth_selection ?? '' }}</td>
                                                        <td>{{ $diag->notes }}</td>
                                                        <td>{{ $diag->treatment_amount ?? '' }}</td>

                                                        <td>
                                                            @if ($diag->treatment_start)
                                                                <span class="badge bg-success">Treatment Start</span>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    onclick="markTreatmentStart({{ $diag->id }})">
                                                                    Treatment Start
                                                                </button>
                                                            @endif
                                                            @if ($diag->treatment_done)
                                                                <span class="badge bg-success">Treatment Done</span>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-primary"
                                                                    onclick="markTreatmentDone({{ $diag->id }})">
                                                                    Treatment Done
                                                                </button>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $patientTreatments->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <!-- Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width : 100px; height : 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this patient treatment?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="treatment_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

    <!-- Document Upload Modal -->
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="documentUploadForm" action="{{ route('document.multipleDocstore', $patient->id ?? 0) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="patient_id" id="modal_patient_id">
                        <input type="hidden" name="treatment_id" id="modal_treatment_id">
                        <input type="hidden" name="patient_treatment_id" id="modal_patient_treatment_id">

                        <div class="mb-3">
                            <label>Document <span class="text-danger">*</span></label>
                            <input type="file" name="document[]" class="form-control"
                                accept="image/jpeg, image/png, application/pdf" multiple required>
                        </div>

                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-primary">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Labwork Modal -->
    <div class="modal fade" id="labworkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Labwork</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="labworkForm" action="{{ route('labworks.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="patient_id" id="work_patient_id">
                        <input type="hidden" name="treatment_id" id="work_treatment_id">
                        <input type="hidden" name="patient_treatment_id" id="work_patient_treatment_id">

                        <div class="mb-3">
                            <label>Lab <span class="text-danger">*</span></label>
                            <select name="lab_id" class="form-control" required>
                                @foreach ($labs->sortBy('lab_name') as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->lab_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Entry Date <span class="text-danger">*</span></label>
                            <input type="date" name="entry_date" id="entry_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-primary">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Modal -->
    <div class="modal fade" id="TreatmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Treatment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="treatmentItemForm">
                        @csrf
                        <input type="hidden" name="patient_id" id="patient_id">
                        <input type="hidden" name="treatment_id" id="treatment_id">
                        <input type="hidden" name="diagnosis_id" id="diagnosis_id">
                        <input type="hidden" name="patient_treatment_id" id="patient_treatment_id">

                        <div class="row g-2 align-items-end">
                            <div class="col-6">
                                <label class="form-label">Treatment <span class="text-danger">*</span></label>
                                <select name="treatment_id" id="treatmentDropdown" class="form-control" required>
                                    @foreach ($treatments->sortBy('treatment_name') as $t)
                                        <option value="{{ $t->id }}">{{ $t->treatment_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sub Treatment </label>
                                <select name="sub_treatment_id" id="subTreatmentDropdown" class="form-control">
                                    <option value="">Select Sub Treatment</option>
                                    {{-- Options will be populated by JS --}}
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="rate" class="form-label">Rate</label>
                                <input type="text" maxlength="10" placeholder="Enter Rate" name="treatment_rate"
                                    id="treatment_rate" class="form-control" value="0"
                                    oninput="this.value = this.value.replace(/[^0-9\-\/]/g, '')">
                            </div>

                            <div class="col-6">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" name="treatment_qty" id="treatment_qty" class="form-control">
                            </div>
                            <div class="col-6">
                                <label for="qty" class="form-label">Amount</label>
                                <input type="number" name="treatment_amount" id="treatment_amount" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-6">
                                <label for="qty" class="form-label">Date</label>
                                <input type="date" name="treatment_date" id="treatment_date"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes </label>
                                <textarea type="text" name="notes" class="form-control" id="notes"></textarea>
                            </div>
                            <div class="col-12 justify-content-end" style="text-align: right">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-3">
                    <h6 class="mb-2">Added Treatments</h6>
                    <div id="treatmentItemsList"><!-- AJAX loads here --></div>
                </div>
            </div>
        </div>
    </div>



@section('scripts')
    {{-- after add treatment reload page --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const treatmentModal = document.getElementById("TreatmentModal");
            treatmentModal.addEventListener("hidden.bs.modal", function() {
                location.reload();
            });
        });
    </script>

    {{-- Subtreatment fetch depend on Treatment --}}
    <script>
        const routeUrl =
            "{{ route('subtreatment.getByTreatment', ['treatment_id' => 'TREATMENT_ID']) }}"; // Note the placeholder for the treatment_id

        $('#treatmentDropdown').on('change', function() {
            const treatmentId = $(this).val();
            const subTreatmentDropdown = $('#subTreatmentDropdown');

            subTreatmentDropdown.empty().append('<option value="">Loading...</option>');

            if (treatmentId) {
                const url = routeUrl.replace('TREATMENT_ID', treatmentId);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        subTreatmentDropdown.empty().append(
                            '<option value="">Select Sub Treatment</option>');
                        if (data.length > 0) {
                            data.forEach(function(item) {
                                subTreatmentDropdown.append(
                                    `<option value="${item.sub_treatment_id}">${item.name}</option>`
                                );
                            });
                        } else {
                            subTreatmentDropdown.append(
                                '<option value="">No sub treatments found</option>');
                        }
                    },
                    error: function() {
                        subTreatmentDropdown.empty().append(
                            '<option value="">Error loading sub treatments</option>');
                    }
                });
            } else {
                subTreatmentDropdown.empty().append('<option value="">Select Sub Treatment</option>');
            }
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const adultBtn = document.getElementById("icon-adult");
            const childBtn = document.getElementById("icon-children");
            const toothSelectionInput = document.getElementById("tooth_selection");
            const toothSearchInput = document.getElementById("tooth_selection_search");

            // === DATA FROM SERVER ===
            const YELLOW_TEETH = @json($yellowTeeth ?? []); // diagnosis (flag=0)
            const GREEN_TEETH = @json($greenTeeth ?? []); // done (flag=1)

            // --- Toggle Adult/Child Teeth ---
            adultBtn?.addEventListener("click", function() {
                document.querySelectorAll(".adult-teeth-group > .row").forEach(row => row.style.display =
                    "flex");
                document.querySelectorAll(".children-teeth-group").forEach(group => group.style.display =
                    "none");
            });
            childBtn?.addEventListener("click", function() {
                document.querySelectorAll(".adult-teeth-group > .row").forEach(row => row.style.display =
                    "none");
                document.querySelectorAll(".children-teeth-group").forEach(group => group.style.display =
                    "flex");
            });

            // === Helpers for visual state ===
            function baselineState(tooth) {
                if (GREEN_TEETH.includes(tooth)) return 'green';
                if (YELLOW_TEETH.includes(tooth)) return 'yellow';
                return 'white';
            }

            function setToothState(img, state, lock = false) {
                img.dataset.state = state;
                img.classList.remove('tooth-green', 'tooth-yellow', 'tooth-neutral');

                if (state === 'green') {
                    img.src = img.dataset.color; // your green PNG
                    img.classList.add('tooth-green');
                } else if (state === 'yellow') {
                    img.src = img.dataset.bw; // your yellow PNG
                    img.classList.add('tooth-yellow');
                } else {
                    img.src = img.dataset.bw; // use yellow PNG base, but filter → gray
                    img.classList.add('tooth-neutral');
                }

                img.dataset.lock = lock ? '1' : '';
                img.style.pointerEvents = lock ? 'none' : '';
            }


            function paintAllFromDB() {
                document.querySelectorAll(".teeth_wrapper img").forEach(img => {
                    const tooth = img.alt;

                    if (GREEN_TEETH.includes(tooth)) {
                        // Done overrides everything
                        setToothState(img, 'green', true);
                    } else if (YELLOW_TEETH.includes(tooth)) {
                        setToothState(img, 'yellow', false);
                    } else {
                        setToothState(img, 'white', false);
                    }
                });
            }

            // Initial paint
            paintAllFromDB();

            // --- Tooth Selection Click ---
            document.querySelectorAll(".teeth_wrapper img").forEach(img => {
                img.addEventListener("click", function() {
                    if (this.dataset.lock === '1') return; // can't change done teeth

                    const toothNumber = this.alt;
                    // current form selection
                    let currentTeeth = (toothSelectionInput?.value || '')
                        .split(",").map(t => t.trim()).filter(t => t !== "");

                    const currentState = this.dataset.state; // our own state tracker

                    if (currentState !== 'green') {
                        // select → make it green
                        setToothState(this, 'green', false);
                        if (!currentTeeth.includes(toothNumber)) currentTeeth.push(toothNumber);
                    } else {
                        // unselect → return to baseline from DB (yellow or white)
                        const base = baselineState(toothNumber);
                        setToothState(this, base, false);
                        currentTeeth = currentTeeth.filter(t => t !== toothNumber);
                    }

                    // sync inputs
                    const joined = currentTeeth.join(", ");
                    if (toothSelectionInput) toothSelectionInput.value = joined;
                    if (toothSearchInput) toothSearchInput.value = joined;

                    updateAmount();
                });
            });

            // --- Sync from Search Box to Main Input (merge values) ---
            if (toothSearchInput) {
                toothSearchInput.addEventListener("input", function() {
                    let searchTeeth = this.value.split(",").map(t => t.trim()).filter(t => t !== "");
                    let currentTeeth = (toothSelectionInput?.value || '')
                        .split(",").map(t => t.trim()).filter(t => t !== "");

                    // Merge and dedupe
                    const merged = [...new Set([...currentTeeth, ...searchTeeth])];

                    // Update both fields
                    if (toothSelectionInput) toothSelectionInput.value = merged.join(", ");
                    toothSearchInput.value = merged.join(", ");

                    // Repaint baseline from DB first…
                    paintAllFromDB();

                    // …then overlay merged selection as green (unless locked)
                    merged.forEach(tooth => {
                        const img = document.querySelector('.teeth_wrapper img[alt="' + tooth +
                            '"]');
                        if (img && img.dataset.lock !== '1') setToothState(img, 'green', false);
                    });

                    updateAmount();
                });
            }

            // --- Rate Change Update ---
            document.getElementById("rate")?.addEventListener("input", updateAmount);

            function updateAmount() {
                let rate = parseFloat(document.getElementById("rate")?.value) || 0;
                let qty = (document.getElementById("tooth_selection")?.value || '')
                    .split(",").filter(t => t.trim() !== "").length;
                document.getElementById("qty").value = qty;
                document.getElementById("amount").value = (rate * qty).toFixed(2);
            }

            // --- Delete Modal ---
            $(".delete-treatment").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('patient_treatments.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl).submit();
            });

            // --- Modal Setters ---
            window.getdatas = function(patient_id, treatment_id, id) {
                $("#modal_patient_id").val(patient_id);
                $("#modal_treatment_id").val(treatment_id);
                $("#modal_patient_treatment_id").val(id);
            };
            window.getid = function(patient_id, treatment_id, id) {
                $("#work_patient_id").val(patient_id);
                $("#work_treatment_id").val(treatment_id);
                $("#work_patient_treatment_id").val(id);
            };
            window.gettreatmentitemid = function(patient_id, treatment_id, id) {
                $("#patient_id").val(patient_id);
                $("#treatment_id").val(treatment_id);
                $("#diagnosis_id").val(id);
            };

            // --- Date Input Year Fix ---
            document.getElementById("entry_date")?.addEventListener("input", function() {
                let parts = this.value.split("-");
                if (parts[0] && parts[0].length > 4) {
                    parts[0] = parts[0].slice(0, 4);
                    this.value = parts.join("-");
                }
            });
        });
    </script>

    <script>
        function markTreatmentDone(id) {
            if (!confirm('Mark this treatment as done?')) return;

            fetch(`{{ route('patient.treatment-items.done', ['id' => '__ID__']) }}`.replace('__ID__', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(j => {
                    if (j.status === 'success') location.reload();
                    else alert('Failed to update.');
                })
                .catch(() => alert('Network error'));
        }
    </script>

    <script>
        function markTreatmentStart(id) {
            if (!confirm('Mark this treatment as Start?')) return;

            fetch(`{{ route('patient.treatment-items.start', ['id' => '__ID__']) }}`.replace('__ID__', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(j => {
                    if (j.status === 'success') location.reload();
                    else alert('Failed to update.');
                })
                .catch(() => alert('Network error'));
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rateInput = document.getElementById("treatment_rate");
            const qtyInput = document.getElementById("treatment_qty");
            const amountInput = document.getElementById("treatment_amount");

            function updateAmount() {
                let rate = parseFloat(rateInput.value) || 0;
                let qty = parseFloat(qtyInput.value) || 0;
                amountInput.value = (rate * qty).toFixed(2);
            }

            // update when either changes
            rateInput.addEventListener("input", updateAmount);
            qtyInput.addEventListener("input", updateAmount);

            // initialize once on page load
            updateAmount();
        });
    </script>

    {{-- treatment modal --}}
    <script>
        // one modal only on the page
        const treatmentModal = document.getElementById('TreatmentModal');

        // when the modal is about to show, get the button that opened it
        treatmentModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // <button ...> that triggered the modal
            const patientId = button.getAttribute('data-patient-id');
            const treatmentId = button.getAttribute('data-treatment-id');
            const diagnosisId = button.getAttribute('data-diagnosis-id');
            const patienttreatmentid = button.getAttribute('data-patient-treatment-id');


            // set hidden fields
            document.getElementById('patient_id').value = patientId;
            document.getElementById('diagnosis_id').value = diagnosisId;
            document.getElementById('patient_treatment_id').value = patienttreatmentid;

            // preselect treatment (optional)
            const sel = document.querySelector('#treatmentItemForm select[name="treatment_id"]');
            const diagonsis_rate = document.querySelector('#treatmentItemForm input[name="diagonsis_rate"]');
            const diagonsis_qty = document.querySelector('#treatmentItemForm input[name="diagonsis_qty"]');
            const diagonsis_amount = document.querySelector('#treatmentItemForm input[name="diagonsis_amount"]');

            if (sel) sel.value = treatmentId ? String(treatmentId) : sel.value;

            // clear & load fresh list
            document.getElementById('treatmentItemsList').innerHTML =
                '<div class="small text-muted">Loading…</div>';

            loadTreatmentItems(patientId, diagnosisId);
        });

        function loadTreatmentItems(patient_id, diagnosis_id) {
            const url = `{{ route('patient.treatment-items.list') }}`;
            const params = new URLSearchParams({
                patient_id,
                diagnosis_id,
                _ts: Date.now() // prevent cached responses
            });

            fetch(url + '?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store',
                    credentials: 'same-origin'
                })
                .then(r => r.text())
                .then(html => document.getElementById('treatmentItemsList').innerHTML = html)
                .catch(() => {
                    document.getElementById('treatmentItemsList').innerHTML =
                        '<div class="text-danger small">Could not load list.</div>';
                });
        }

        // Add treatment
        document.getElementById('treatmentItemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const fd = new FormData(form);
            if (!fd.get('treatment_qty')) fd.delete('treatment_qty');
            if (!fd.get('treatment_rate')) fd.delete('treatment_rate');
            if (!fd.get('treatment_amount')) fd.delete('treatment_amount');
            if (!fd.get('treatment_date')) fd.delete('treatment_date');
            if (!fd.get('sub_treatment_id')) fd.delete('sub_treatment_id');

            fetch(`{{ route('patient.treatment-items.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                        'Accept': 'application/json'
                    },
                    body: fd
                })
                .then(r => r.json())
                .then(json => {
                    if (json.status === 'success') {
                        const pid = document.getElementById('patient_id').value;
                        const ptid = document.getElementById('diagnosis_id').value;
                        loadTreatmentItems(pid, ptid);

                        // clear fields after submit
                        form.reset();
                    } else {
                        alert('Unable to add.');
                    }
                })
                .catch(() => alert('Network error.'));
        });

        // Delete
        function deleteTreatmentItem(id) {
            if (!confirm('Delete this item?')) return;

            $.ajax({
                url: `{{ route('patient.treatment-items.destroy', ['id' => '__ID__']) }}`.replace('__ID__', id),
                type: 'POST', // use POST + method spoofing
                data: {
                    _method: 'DELETE'
                }, // Laravel will treat as DELETE
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(resp) {
                    if (resp && resp.status === 'success') {
                        const pid = $('#patient_id').val();
                        const did = $('#diagnosis_id').val();
                        if (typeof loadTreatmentItems === 'function') loadTreatmentItems(pid, did);
                    } else {
                        alert((resp && resp.message) ? resp.message : 'Unable to delete.');
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || xhr.responseText || 'Unknown error';
                    alert('Delete failed: ' + msg);
                }
            });
        }
    </script>

    <script>
        // Select all
        document.getElementById('checkAll').addEventListener('change', function() {
            document.querySelectorAll('.treatment-check').forEach(cb => cb.checked = this.checked);
            toggleAddBtn();
        });

        // Enable/disable button based on selection
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('treatment-check')) toggleAddBtn();
        });

        function toggleAddBtn() {
            const anyChecked = document.querySelectorAll('.treatment-check:checked').length > 0;
            document.getElementById('addToQuoteBtn').disabled = !anyChecked;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize select2
            $('#diagnosis_list').select2({
                placeholder: "Select Diagnosis",
                allowClear: true,
                width: '100%'
            });

            // Example: populate dynamically by type
            document.getElementById("diagnosis_type").addEventListener("change", function() {
                let type = this.value;
                $('#diagnosis_list').html('<option value="">Loading...</option>');

                let url = "{{ route('Diagnosis.byType', ':type') }}".replace(':type', type);

                fetch(url).then(response => response.json())
                    .then(data => {
                        $('#diagnosis_list').empty(); // clear existing
                        if (data.length > 0) {
                            data.forEach(diag => {
                                let option = new Option(diag.Diagnosis_name, diag.id, false,
                                    false);
                                $('#diagnosis_list').append(option);
                            });
                        } else {
                            $('#diagnosis_list').append(
                                '<option value="">No diagnosis available</option>');
                        }
                    })
                    .catch(() => {
                        $('#diagnosis_list').html('<option value="">Error loading</option>');
                    });
            });
        });
    </script>

@endsection
