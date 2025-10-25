@extends('layouts.app')

@section('title', 'Patient Notes')

@section('content')
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

                {{-- Alert Messages --}}
                @include('common.alert')

                @include('patient.show', ['id' => $patient->id]) <!-- ✅ Patient details included -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">

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
                                 <input type="hidden" id="tooth_selection" value="{{ $toothSelection ?? '' }}">

                                <form action="{{ route('patient_notes.index', $patient->id) }}" method="GET" id="toothSearchForm" class="d-flex gap-2">
                                <input type="hidden" name="tooth_selection" id="tooth_selection_search" value="{{ $toothSelection ?? '' }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('patient_notes.index', $patient->id) }}" class="btn btn-primary">Reset</a>
                                </form>


                            </section>
                        </div>

                         <!-- Add Note Section -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Note</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('patient_notes.store', $patient->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="patient_treatment_id" name="patient_treatment_id" value="">
                                    <div class="mb-3">
                                        <label class="form-label">Treatment <span class="text-danger">*</span></label>
                                        <select name="treatment_id" id="treatment_id" class="form-control" required>
                                            <option value="">Select Treatment</option>
                                            @foreach ($treatments as $t)
                                                <option value="{{ $t->id }}" data-patient_treatment_id="{{ $t->patient_treatment_id }}">{{ $t->treatment_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                     <div class="mb-3">
                                        <label class="form-label">Tooth<span class="text-danger">*</span></label>
                                        <select name="tooth_number" id="tooth_number" class="form-control">
                                            <option value="">Select Tooth</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" rows="3" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Note <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="notes" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Document </label>
                                        <input type="file" name="document[]" class="form-control"
                                            accept="image/jpeg, image/jpg, image/png, application/pdf," multiple>
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
                </div>
                   

                    <!-- Notes List Section -->
                     @if($notes->count() > 0)
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notes List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Treatment</th>
                                            <th>Tooth</th>
                                            <th>Note</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notes as $key => $note)
                                            <tr>
                                                <td>{{ $notes->firstItem() + $key }}</td>
                                                <td>{{ $note->treatment->treatment_name ?? '' }}</td>
                                                <td>{{ $note->tooth_number }}</td>
                                                <td>{{ $note->notes }}</td>
                                                <td>{{ $note->date ? date('d-m-Y', strtotime($note->date)) : '-' }}</td>
                                                <td>
                                                    <a href="{{ route('patient_notes.viewdocument', [$note->treatment_id,$patient->id]) }}"
                                                        class="btn btn-sm btn-primary" title="View Document">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $note->id }}" data-notes="{{ $note->notes }}"
                                                        data-patient-id="{{ $patient->id }}"
                                                        data-date="{{ $note->date }}"
                                                        data-treatment-id="{{ $note->treatment_id }}"
                                                        data-bs-toggle="modal" data-bs-target="#editNoteModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $note->id }}"
                                                        data-patient-id="{{ $patient->id }}" data-toggle="modal"
                                                        data-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $notes->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Treatment <span class="text-danger">*</span></label>
                            <select name="treatment_id" class="form-control" required>
                                <option value="">Select Treatment</option>
                                @foreach ($treatments as $t)
                                    <option value="{{ $t->id }}">{{ $t->treatment_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" id="editdate" required>
                        </div>
                        <div class="mb-3">
                            <label>Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="notes" id="editNotes" rows="3" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
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
                            colors="primary:#f7b84b,secondary:#f06548" style="width: 100px; height: 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this note?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="document_id" id="deleteid" value="">
                            <button type="submit" class="btn btn-primary">Yes, Delete It!</button>
                        </form>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->


@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // jQuery or vanilla JavaScript can be used for AJAX request
    $(document).ready(function() {
        $('#treatment_id').change(function() {
            var treatmentId = $(this).val();
            var patientTreatmentId = $('#treatment_id option:selected').data('patient_treatment_id'); // Get the data-patient_treatment_id of the selected option
            $('#patient_treatment_id').val(patientTreatmentId);
           
            
            if(treatmentId) {
                // Send AJAX request to fetch tooth numbers
                $.ajax({
                    url: '/get-tooth-numbers/' + treatmentId,  // Make sure the URL matches your route
                    type: 'GET',
                    success: function(data) {
                        $('#tooth_number').empty().append('<option value="">Select Tooth</option>');

                        if(data.tooth_numbers.length > 0) {
                            $.each(data.tooth_numbers, function(index, tooth) {
                                $('#tooth_number').append('<option value="'+ tooth +'">Tooth ' + tooth + '</option>');
                            });
                        } else {
                            $('#tooth_number').append('<option value="">No Tooth Available</option>');
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching tooth numbers.');
                    }
                });
            } else {
                $('#tooth_number').empty().append('<option value="">Select Tooth</option>');
            }
        });
    });
</script>
    <script>
        $(document).ready(function() {
            // Open Edit Modal & Load Data
            $(".edit-btn").on("click", function() {
                let id = $(this).data("id");
                let notes = $(this).data("notes");
                let date = $(this).data("date");
                let treatmentId = $(this).data("treatment-id");
                let patientId = $(this).data("patient-id");

                $("#editNotes").val(notes);
                // normalize to YYYY-MM-DD for <input type="date">
                let iso = "";
                if (date) {
                    // try to detect D-M-Y and swap
                    const dmy = /^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(date);
                    const ymd = /^(\d{4})[-\/](\d{2})[-\/](\d{2})$/.exec(date);
                    if (dmy) {
                        iso = `${dmy[3]}-${dmy[2]}-${dmy[1]}`;
                    } else if (ymd) {
                        iso = `${ymd[1]}-${ymd[2]}-${ymd[3]}`;
                    }
                }
                $("#editdate").val(iso);
                const $select = $('#editNoteModal select[name="treatment_id"]');
                $select.val(String(treatmentId)).trigger('change');

                let actionUrl = "{{ route('patient_notes.update', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);

                $("#editForm").attr("action", actionUrl);
            });

            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                let patientId = $(this).data("patient-id");

                // Set the delete form action dynamically
                let actionUrl = "{{ route('patient_notes.destroy', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);

                $("#deleteForm").attr("action", actionUrl);
                $("#deleteRecordModal").modal("show");
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });
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

             // ✅ NEW: Green overlay based on a CSV list (search or selection)
            function applySelectionFromString(str) {
                const teeth = String(str || "")
                .split(",")
                .map(t => t.trim())
                .filter(Boolean);

                // Repaint baseline first (yellow/white/locked green)
                paintAllFromDB();

                // Then overlay the searched/selected teeth in green (unless locked)
                teeth.forEach(tooth => {
                const img = document.querySelector('.teeth_wrapper img[alt="' + tooth + '"]');
                if (img && img.dataset.lock !== '1') {
                    setToothState(img, 'green', false);
                }
                });
            }

            // Initial paint
            paintAllFromDB();
            applySelectionFromString(toothSearchInput?.value);

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
        });
    </script>

   
@endsection
