<div class="card">
    <div class="header">
        <h2>
            WITHDRAW LIST
        </h2>
        <ul class="header-dropdown m-r--5">
            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="javascript:void(0);" class=" waves-effect waves-block">
                            Action
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class=" waves-effect waves-block">
                            Another action
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class=" waves-effect waves-block">
                            Something else here
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="body">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#home_with_icon_title" data-toggle="tab">
                    <i class="material-icons">list</i> LIST
                </a>
            </li>
            <li role="presentation">
                <a href="#profile_with_icon_title" data-toggle="tab">
                    <i class="material-icons">insert_drive_file</i> FORM
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home_with_icon_title">
                @include('profit.withdraw_list')
            </div>
            <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                @include('profit.withdraw_form')
            </div>
        </div>
    </div>
</div>