@extends('layouts.app')
@section('title', 'Add Treatments')
@section('content')
    <!-- style.css -->
    <link rel="stylesheet" href="assets/css/style.css">
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

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])


                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title m-3">Add Patient Treatment</h5>
                    <span id ="icon-adult" class="ms-3 fs-3"><i class="fa fa-user"></i></span>
                    <span id ="icon-children" class="ms-3 fs-3"><i class="fa fa-child"></i></span>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <section>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-6 "
                                        style="border-right : 1px solid grey; padding: 20px;">
                                        <div class="heading mb-3">Upper Right(1)</div>
                                        <div class="adult-teeth-group">
                                            <div class="row d-flex justify-content-between p-2">
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/18.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/18.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/18.png') }}"
                                                            alt="18">
                                                        <p>18</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/17.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/17.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/17.png') }}"
                                                            alt="17">
                                                        <p>17</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/16.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/16.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/16.png') }}"
                                                            alt="16">
                                                        <p>16</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/15.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/15.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/15.png') }}"
                                                            alt="15">
                                                        <p>15</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/14.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/14.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/14.png') }}"
                                                            alt="14">
                                                        <p>14</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/13.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/13.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/13.png') }}"
                                                            alt="13">
                                                        <p>13</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/12.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/12.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/12.png') }}"
                                                            alt="12">
                                                        <p>12</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/1E.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/1E.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/1E.png') }}"
                                                                alt="55">
                                                            <p>55</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/1D.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/1D.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/1D.png') }}"
                                                                alt="54">
                                                            <p>54</p>
                                                        </div>

                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/1C.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/1C.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/1C.png') }}"
                                                                alt="53">
                                                            <p>53</p>
                                                        </div>

                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/1B.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/1B.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/1B.png') }}"
                                                                alt="52">
                                                            <p>52</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper adult-teeth">
                                                        <img src="{{ asset('assets/images/TeethYellow/21.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/21.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/21.png') }}"
                                                            alt="21">
                                                        <p>21</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/22.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/22.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/22.png') }}"
                                                            alt="22">
                                                        <p>22</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/23.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/23.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/23.png') }}"
                                                            alt="23">
                                                        <p>23</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/24.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/24.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/24.png') }}"
                                                            alt="24">
                                                        <p>24</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/25.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/25.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/25.png') }}"
                                                            alt="25">
                                                        <p>25</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/26.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/26.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/26.png') }}"
                                                            alt="26">
                                                        <p>26</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/27.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/27.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/27.png') }}"
                                                            alt="27">
                                                        <p>27</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper adult-teeth">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/2A.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/2A.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/2A.png') }}"
                                                                alt="61">
                                                            <p>61</p>
                                                        </div>



                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/2B.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/2B.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/2B.png') }}"
                                                                alt="62">
                                                            <p>62</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/2C.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/2C.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/2C.png') }}"
                                                                alt="63">
                                                            <p>63</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/2D.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/2D.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/2D.png') }}"
                                                                alt="64">
                                                            <p>64</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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

                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/48.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/48.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/48.png') }}"
                                                            alt="48">
                                                        <p>48</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/47.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/47.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/47.png') }}"
                                                            alt="47">
                                                        <p>47</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/46.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/46.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/46.png') }}"
                                                            alt="46">
                                                        <p>46</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/45.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/45.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/45.png') }}"
                                                            alt="45">
                                                        <p>45</p>
                                                    </div>

                                                </div>

                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/44.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/44.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/44.png') }}"
                                                            alt="44">
                                                        <p>44</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/43.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/43.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/43.png') }}"
                                                            alt="43">
                                                        <p>43</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/42.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/42.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/42.png') }}"
                                                            alt="42">
                                                        <p>42</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/3E.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/3E.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/3E.png') }}"
                                                                alt="75">
                                                            <p>75</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/4D.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/4D.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/4D.png') }}"
                                                                alt="74">
                                                            <p>74</p>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/4C.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/4C.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/4C.png') }}"
                                                                alt="73">
                                                            <p>73</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/4B.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/4B.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/4B.png') }}"
                                                                alt="72">
                                                            <p>72</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/31.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/31.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/31.png') }}"
                                                            alt="31">
                                                        <p>31</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/32.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/32.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/32.png') }}"
                                                            alt="32">
                                                        <p>32</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/33.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/33.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/33.png') }}"
                                                            alt="33">
                                                        <p>33</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/34.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/34.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/34.png') }}"
                                                            alt="34">
                                                        <p>34</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/35.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/35.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/35.png') }}"
                                                            alt="35">
                                                        <p>35</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/36.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/36.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/36.png') }}"
                                                            alt="36">
                                                        <p>36</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                    <div class="teeth_wrapper">
                                                        <img src="{{ asset('assets/images/TeethYellow/37.png') }}"
                                                            data-color="{{ asset('assets/images/TeethGreen/37.png') }}"
                                                            data-bw="{{ asset('assets/images/TeethYellow/37.png') }}"
                                                            alt="37">
                                                        <p>37</p>
                                                    </div>

                                                </div>
                                                <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper adult-teeth">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/3A.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/3A.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/3A.png') }}"
                                                                alt="81">
                                                            <p>81</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/3B.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/3B.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/3B.png') }}"
                                                                alt="82">
                                                            <p>82</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/3C.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/3C.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/3C.png') }}"
                                                                alt="83">
                                                            <p>83</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
                                                        <div class="teeth_wrapper">
                                                            <img src="{{ asset('assets/images/ChildYellowTeeth/3D.png') }}"
                                                                data-color="{{ asset('assets/images/ChildGreenTeeth/3D.png') }}"
                                                                data-bw="{{ asset('assets/images/ChildYellowTeeth/3D.png') }}"
                                                                alt="84">
                                                            <p>84</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 col-md-3 col-sm-3 col-3">
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
                        </section>
                    </div>


                    <hr>
                    <div class="col-lg-12 mt-2">
                        <div class="card-body">
                            <form action="{{ route('patient_treatments.store', $patient->id) }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="treatment_id" class="form-label">Treatment<span
                                                class="text-danger">*</span></label>
                                        <select name="treatment_id" class="form-control" required>
                                            <option value="" disabled selected>Select Treatment</option>
                                            @foreach ($treatments->sortBy('treatment_name') as $treatment)
                                                <option value="{{ $treatment->id }}">{{ $treatment->treatment_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tooth_selection" class="form-label">Tooth Selection<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="tooth_selection" maxlength="50" id="tooth_selection"
                                            class="form-control" placeholder="E.g., 12, 14">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label for="rate" class="form-label">Rate</label>
                                        <input type="text" maxlength="10" placeholder="Enter Rate" name="rate"
                                            id="rate" class="form-control" value="0"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="qty" class="form-label">Quantity<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="qty" id="qty" class="form-control"
                                            readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label for="amount" class="form-label">Amount<span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="amount" id="amount"
                                            class="form-control" readonly>
                                    </div>


                                    <div class="col-md-6 mt-3">
                                        <label for="Comment" class="form-label">Comment<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="comment" id="comment" class="form-control">
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

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const adultBtn = document.getElementById("icon-adult");
                        const childBtn = document.getElementById("icon-children");

                        adultBtn.addEventListener("click", function() {
                            document.querySelectorAll(".adult-teeth-group > .row").forEach(row => row.style.display =
                                "flex");
                            document.querySelectorAll(".children-teeth-group").forEach(group => group.style.display =
                                "none");
                        });

                        childBtn.addEventListener("click", function() {
                            document.querySelectorAll(".adult-teeth-group > .row").forEach(row => row.style.display =
                                "none");
                            document.querySelectorAll(".children-teeth-group").forEach(group => group.style.display =
                                "flex");
                        });
                        let toothSelectionInput = document.getElementById("tooth_selection");
                        let alreadySelectedTeeth = @json($selectedTeeth ?? []); // e.g. ["11","12","13"]
                        alreadySelectedTeeth.forEach(tooth => {
                            let img = document.querySelector('.teeth_wrapper img[alt="' + tooth + '"]');
                            if (img) {
                                img.style.pointerEvents = "none";
                                img.style.opacity = "0.6";
                                img.style.filter = "hue-rotate(50deg)";
                                // img.src = img.dataset.color;
                            }
                        });
                        toothSelectionInput.value = "";

                        // --- New tooth selection ---
                        document.querySelectorAll(".teeth_wrapper img").forEach(img => {
                            img.addEventListener("click", function() {
                                let toothNumber = this.alt;
                                let currentTeeth = toothSelectionInput.value.split(",").map(t => t.trim())
                                    .filter(t => t !== "");

                                // Toggle
                                if (this.src.includes(this.dataset.bw)) {
                                    this.src = this.dataset.color; // yellow → green
                                    if (!currentTeeth.includes(toothNumber)) {
                                        currentTeeth.push(toothNumber);
                                    }
                                } else {
                                    this.src = this.dataset.bw; // green → yellow
                                    currentTeeth = currentTeeth.filter(t => t !== toothNumber);
                                }

                                toothSelectionInput.value = currentTeeth.join(", ");
                                updateAmount();
                            });
                        });

                        document.getElementById("rate").addEventListener("input", updateAmount);

                        function updateAmount() {
                            let rate = parseFloat(document.getElementById("rate").value) || 0;
                            let qty = document.getElementById("tooth_selection").value.split(",").filter(t => t.trim() !== "")
                                .length;
                            document.getElementById("qty").value = qty;
                            document.getElementById("amount").value = (rate * qty).toFixed(2);
                        }
                    });
                </script>
            @endsection
