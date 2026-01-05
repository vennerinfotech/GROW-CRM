<div class="card count-{{ @count($occasions) }}" id="occasions-table-wrapper">
    <div class="card-body">
        <div class="table-responsive">
            @if (@count($occasions) > 0)
            <table id="demo-foo-addrow" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        <th class="occasions_col_name">{{ cleanLang(__('lang.name')) }}</th>
                        <th class="occasions_col_date">{{ cleanLang(__('lang.date_created')) }}</th>
                        <th class="occasions_col_creator">{{ cleanLang(__('lang.created_by')) }}</th>
                        <th class="occasions_col_action"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    @foreach($occasions as $occasion)
                    <!--tr-->
                    <tr class="item-row" id="occasion_{{ $occasion->leadoccasions_id }}">
                        <td class="occasions_col_name">
                            {{ $occasion->leadoccasions_title }}
                        </td>
                        <td class="occasions_col_date">
                            {{ runtimeDate($occasion->leadoccasions_created) }}
                        </td>
                        <td class="occasions_col_creator">
                            <img src="{{ getUsersAvatar($occasion->avatar_directory, $occasion->avatar_filename) }}" alt="user"
                                class="img-circle avatar-xsmall">
                            {{ $occasion->first_name }}
                        </td>
                        <td class="occasions_col_action">
                            <!--edit-->
                            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                data-toggle="modal" data-target="#commonModal"
                                data-url="{{ url('/settings/occasions/'.$occasion->leadoccasions_id.'/edit') }}"
                                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_occasion')) }}"
                                data-action-url="{{ url('/settings/occasions/'.$occasion->leadoccasions_id) }}"
                                data-action-method="PUT" data-action-ajax-class=""
                                data-action-ajax-loading-target="occasions-td-container">
                                <i class="sl-icon-note"></i>
                            </button>
                            <!--delete-->
                            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                                data-url="{{ url('/') }}/settings/occasions/{{ $occasion->leadoccasions_id }}">
                                <i class="sl-icon-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    <!--tr-->
                </tbody>
            </table>
            @else
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>
