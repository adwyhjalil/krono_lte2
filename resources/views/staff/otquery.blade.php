@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<h1>Pending Verification Claim</h1>
<div class="panel panel-main panel-default">
    <div class="panel-body">
        
        @if(count($otlist)!=0)
        <form action="{{route('ot.query')}}" method="POST" style="display:inline"> 
            @csrf    
            <div class="table-responsive">
                <table id="tOTList" class="table table-bordered">
                    <thead style="background: grey">
                        <tr>
                            <th>No</th>
                            <th>Reference No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Start OT</th>
                            <th>End OT</th>
                            <th>Total Hours/Minutes</th>
                            <th>Charge Code</th>
                            <th>Amount (Estimated)</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Action Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($otlist as $no=>$singleuser)
                        <tr>
                            <input type="text" class="form-control hidden" id="inputid" name="inputid[]" value="{{$singleuser->id}}" required>
                            <td>{{++$no}}</td>
                            <td>
                                <a id="a-{{$no}}" data-id="{{$singleuser->id}}">{{ $singleuser->refno }}</a>
                            </td>
                            <td>{{ $singleuser->name->name }}</td>
                            <td>{{ date("d.m.Y", strtotime($singleuser->date)) }}</td>
                            <td>
                                @foreach($singleuser->detail as $details)
                                    {{date('Hi', strtotime($details->start_time)) }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($singleuser->detail as $details)
                                    {{ date('Hi', strtotime($details->end_time))}}<br>
                                @endforeach
                            </td>
                            <td>{{ $singleuser->total_hour }}h/{{ $singleuser->total_minute }}m</td>
                            <td>{{$singleuser->charge_type}}</td>
                            <td>RM{{$singleuser->amount}}</td>
                            <td>
                                @if($singleuser->status=="PA")
                                    <p>Pending Approval</p>
                                @elseif($singleuser->status=="PV")
                                    <p>Pending Verification</p>
                                @endif
                            </td>
                            <td>
                                <select name="inputaction[]" id="action-{{$no}}">
                                    <option selected value="">Select Action</option>
                                    <!-- <option hidden disabled selected value="">Select Action</option> -->
                                    @if($singleuser->status=="PV")<option value="PA">Verify</option>
                                    @elseif($singleuser->status=="PA")<option value="A">Approve</option>
                                    @endif
                                    <option value="Q2">Query</option>
                                </select>
                            </td>
                            <td>
                                <textarea rows = "1" cols="40" type="text"  id="inputremark-{{$no}}" name="inputremark[]" value="" placeholder="" style="resize: none; display: inline" disabled></textarea>
                            </td>
                        </tr>
                        {{--<!-- <tr style="text-align:center; display: none" id="remark-{{$no}}">
                            <td colspan="11">
                                <span style="position: relative; top: -30px;"><b>Query Remark: </b></span>
                                <textarea rows = "2" cols = "100" type="text"  id="inputremark-{{$no}}" name="inputremark[]" value="" placeholder="Write justification" style="resize: none; display: inline"></textarea>
                            </td>
                        </tr> -->--}}
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="submitbtn" class="text-center" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')">
                <button type="submit" class="btn btn-primary">SUBMIT</button>
            </div>
        </form>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reference No</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Start OT</th>
                        <th>End OT</th>
                        <th>Total Hours/Minutes</th>
                        <th>Charge Code</th>
                        <th>Amount (Estimated)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="10"><div class="text-center"><i>Not available</i></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
<form action="{{route('ot.detail')}}" method="POST" class="hidden" id="form">
    @csrf
    <input type="text" class="hidden" name="detailid" id="detailid" value="" required>
    <input type="text" class="hidden" name="type" value="query" required>
</form>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tOTList').DataTable({
            "responsive": "true",
            // "order" : [[1, "asc"]],
            "searching": false,
            "bSort": false
        });

        // var t = $('#time').DataTable({
        //     "responsive": "true",
        //     // "order" : [[1, "asc"]],
        //     "searching": false,
        //     "bSort": false
        // });

        // t.on( 'order.dt search.dt', function () {
        //     t.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     });
        // }).draw();
    });
    
    function yes(i){
        return function(){
            var id = $("#a-"+i).data('id');
            $("#detailid").val(id);
            // alert($("#inputid").val());
            $("#form").submit();
        }
    }

    for(i=1; i<{{count($otlist)+1}}; i++){
        $("#a-"+i).on("click", yes(i));
    }

    function remark(i){
        return function(){
            if($("#action-"+i).val()=="Q2"){
                // $('#remark-'+i).css("display", "table-row");
                Swal.fire({
                    title: 'Remarks',
                    input: 'textarea',
                    inputPlaceholder: 'This is mandatory field. Please key in remarks here!',
                    inputAttributes: {
                        'aria-label': 'This is mandatory field. Please key in remarks here!'
                    },
                    html: "<p>Are you sure to query this claim application?</p>",
                    confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true,
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                        if (result.value) {
                            
                            $("#inputremark-"+i).prop('disabled',false);
                            $("#inputremark-"+i).prop('required',true);
                            $("#inputremark-"+i).val(result.value);
                            
                        }else{
                            
                            
                            $("#action-"+i).val("");
                            $("#inputremark-"+i).prop('disabled',true);
                            $("#inputremark-"+i).val("");
                            $("#inputremark-"+i).prop('required',false);
                        }
                })
            }else{
                // $('#remark-'+i).css("display", "none");
                Swal.fire({
                    title: 'Terms and Conditions',
                    input: 'checkbox',
                    inputValue: 0,
                    inputPlaceholder:
                        "<p>By clicking on <span style='color: #143A8C'>\"Yes\"</span> button below, you are agreeing to the above related terms and conditions</p>",
                        html: "<p>I hereby certify that my claim is compliance with company's term and condition on <span style='font-weight: bold'>PERJANJIAN BERSAMA, HUMAN RESOURCE MANUAL, and BUSINESS PROCESS MANUAL</span> If deemed falsed, disciplinary can be imposed on me.</p>",
                        confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true,
                    confirmButtonColor: '#EF7202',
                    cancelButtonColor: 'transparent',
                    inputValidator: (result) => {
                        return !result && 'You need to agree with T&C'
                    }
                }).then((result) => {
                    if (result.value) {
                        // whensubmit = false;
                        @if($singleuser->status=="PV")$("#action-"+i).val("PA");
                        @elseif($singleuser->status=="PA")$("#action-"+i).val("A");
                        @endif
                        $("#inputremark-"+i).prop('disabled',true);
                            $("#inputremark-"+i).val("");
                            $("#inputremark-"+i).prop('required',false);
                    }else{
                        
                        $("#action-"+i).val("");
                        $("#inputremark-"+i).prop('disabled',true);
                            $("#inputremark-"+i).prop('required',false);
                    }
                })
            }
        };
    };

    function remark2(i){
        return function(){
            // alert("");
            if($("#action-"+i).val()=="Q2"){
                var str = $("#inputremark-"+i).val();
                Swal.fire({
                    title: 'Remarks',
                    input: 'textarea',
                    inputValue: str,
                    inputAttributes: {
                        'aria-label': 'This is mandatory field. Please key in remarks here!'
                    },
                    html: "<p>Are you sure to query this claim application?</p>",
                    confirmButtonText:
                        'YES',
                        cancelButtonText: 'NO',
                    showCancelButton: true
                    }).then((result) => {
                        if (result.value) {
                            
                            $("#inputremark-"+i).prop('disabled',false);
                            $("#inputremark-"+i).prop('required',true);
                            $("#inputremark-"+i).val(result.value);
                            
                        }else{
                            
                            
                        $("#action-"+i).val("");
                            $("#inputremark-"+i).prop('disabled',true);
                            $("#inputremark-"+i).val("");
                            $("#inputremark-"+i).prop('required',false);
                            
                        }
                })
            }
        }
    }

    for (i=1; i<{{count($otlist)+1}}; i++) {
        $("#action-"+i).change(remark(i));
        $("#inputremark-"+i).on("click",(remark2(i)));
    }

    @if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
    @endif
</script>
@stop