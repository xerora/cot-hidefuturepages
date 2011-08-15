<!-- BEGIN: MAIN -->
	<script type="text/javascript">
	$(document).ready(function() {
		$("#hfp_options_nojs").remove();
		$("#hfp_options_js").show();
		$("#hfp_select_options").change(function() {
			var state_selected = $("#hfp_select_options").val();
			switch(state_selected) {
				case '3':
					window.location = "{TOOL_STATE_SELECTED_URL_FUTURE}";
				break;
				case '4':
					window.location = "{TOOL_STATE_SELECTED_URL_HIDDEN}";
				break;
			}
		});
	});
	</script>
	<link href="{HFP_TOOL_STYLESHEET_HREF}" rel="stylesheet" type="text/css" />
	<div id="hfp_tool_content">

		<!-- BEGIN: ACTION_SHOWALL -->
			<h3>{TOOL_SHOW_TITLE}</h3>
			<div style="margin-top: 10px; margin-bottom: 10px;">
				Show pages marked as:&nbsp; {TOOL_SHOW_OPTIONS}
			</div>

			<!-- BEGIN: EMPTY_LIST -->
				<p style="margin-top: 20px;">
					There are currently no pages to show.
				</p>
			<!-- END: EMPTY_LIST -->
			
			<!-- BEGIN: NONEMPTY_LIST -->
			<ul class="hfp_list_container" style="width: 100%; margin-top: 15px; margin-bottom: 15px;">
				<li class="hfp_list_item" style="width: 48%;">
					<strong>Title</strong> &nbsp;
					<a href="{ITEM_SORT_TITLE_ASC}"><img class="hfp_icon_12" src="{PHP.tool_path}/img/arrow-up.gif" /></a> &nbsp;
					<a href="{ITEM_SORT_TITLE_DESC}"><img class="hfp_icon_12" src="{PHP.tool_path}/img/arrow-down.gif" /></a>
				</li>
				<li class="hfp_list_item" style="width: 18%;"><strong>Begins</strong> &nbsp;
					<a href="{ITEM_SORT_BEGIN_ASC}"><img class="hfp_icon_12" src="{PHP.tool_path}/img/arrow-up.gif" /></a> &nbsp;
					<a href="{ITEM_SORT_BEGIN_DESC}"><img class="hfp_icon_12" src="{PHP.tool_path}/img/arrow-down.gif" /></a>				
				</li>
				<li class="hfp_list_item" style="width: 18%;"><strong>Expires</strong> &nbsp;
					<a href="{ITEM_SORT_EXPIRE_ASC}"><img class="hfp_icon_12" src="{PHP.tool_path}/img/arrow-up.gif" /></a> &nbsp;
					<a href="{ITEM_SORT_EXPIRE_DESC}"><img class="hfp_icon_12" src="{PHP.tool_path}/img/arrow-down.gif" /></a>				
				</li>
				<li class="hfp_list_item" style="width: 16%;"><strong>Options</strong>&nbsp;		
				</li>
			</ul>

			<!-- BEGIN: ITEM_LIST -->
			<ul class="hfp_list_container hfp_item_list">
				<li class="hfp_list_item" style="width: 48%;"><img src="./images/admin/page.gif" /> &nbsp;<a href="{ITEM_PAGE_EDIT_URL}">{ITEM_PAGE_TITLE}</a>&nbsp;</li>
				<li class="hfp_list_item" style="width: 18%;">{ITEM_PAGE_BEGIN}&nbsp;</li>
				<li class="hfp_list_item" style="width: 18%;">{ITEM_PAGE_EXPIRE}&nbsp;</li>
				<li class="hfp_list_item" style="width: 16%;">

					<a href="{ITEM_PAGE_EDIT_URL}"><img title="Edit page" src="{PHP.tool_path}/img/page_edit.png" /></a> &nbsp; &nbsp; &nbsp;
					<a href="{ITEM_PAGE_ADD_TO_QUEUE_URL}"><img title="Add page to validation queue" src="{PHP.tool_path}/img/page_add.png" /></a> &nbsp; &nbsp; &nbsp;
					<a href="{ITEM_PAGE_SET_TO_DISPLAY_URL}"><img title="Set page to display now" src="{PHP.tool_path}/img/page_go.png" /></a>

				</li>
			</ul>
			<!-- END: ITEM_LIST -->
			<ul class="hfp_list_container" style="margin-top: 20px; margin-bottom: 10px;">
				<li class="hfp_list_item">
					{ITEM_PAGE_PAGINATION}
				</li>
				<li style="float: right;">
					Showing {TOOL_SHOW_PAGELIMIT} of {TOOL_SHOW_COUNT} items.
				</li>
			</ul>
			<hr class="hfp_hr" />
			<div style="margin-top: 10px;">
				<strong>Legend:</strong>
				<ul class="hfp_list_container" style="margin-top: 15px;">
					<li class="hfp_list_item" style="margin-right: 30px;">
						<img title="Edit page" src="{PHP.tool_path}/img/page_edit.png" /> &nbsp;Edit page
					</li>
					<li class="hfp_list_item" style="margin-right: 30px;">
						<img title="Add page to validation queue" src="{PHP.tool_path}/img/page_add.png" /> &nbsp;Add page to validation queue
					</li>
					<li class="hfp_list_item">
						<img title="Set page to display now" src="{PHP.tool_path}/img/page_go.png" /> &nbsp;Set page to display now
					</li>
				</ul>
			</div>
			<!-- END: NONEMPTY_LIST -->
		<!-- END: ACTION_SHOWALL -->
	</div>
<!-- END: MAIN -->
