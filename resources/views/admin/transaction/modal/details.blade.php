@php
   $tnx = (!empty($transaction)) ? $transaction : false;
   $user = (!empty($profile)) ? $profile : false;
@endphp

<div class="modal-dialog modal-dialog-centered modal-{{ ($show=='profile') ? 'md' : 'lg' }}" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">

        @if(!empty($tnx))
            @if($show=='profile') 
                @include('admin.transaction.modal.details-profile', ['tnx' => $tnx, 'user' => $user])
            @else
                @include('admin.transaction.modal.details-view', ['tnx' => $tnx, 'user' => $user])
            @endif
        @else 
            {{ __("Nothing found!") }}
        @endif

        </div>
    </div>
</div>
            