<?php
$currentUrl = url()->current();
?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<div id="hwpwrap">
	<div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
		<div id="wpwrap">
			<div id="wpcontent">
				<div id="wpbody">
					<div id="wpbody-content">

						<div class="wrap">

							<div class="manage-menus">
								<form method="get" action="{{ $currentUrl }}">
									<label for="menu" class="selected-menu">{{ translate('Select the menu you want to edit') }}:</label>

									{!! Menu::select('menu', $menulist) !!}

									<span class="submit-btn">
										<input type="submit" class="button-secondary" value="Choose">
									</span>
									
									<span class="add-new-menu-action"> {{ translate('or') }} <a href="{{ $currentUrl }}?action=edit&menu=0">{{ translate('Create new menu') }}</a>. </span>
								</form>
							</div>
							<div id="nav-menus-frame">

								@if(request()->has('menu')  && !empty(request()->input("menu")))
								<div id="menu-settings-column" class="metabox-holder">

									{{-- CUSTOM LINK --}}
									<div class="clear"></div>

									<form id="nav-menu-meta" action="" class="nav-menu-meta" method="post" enctype="multipart/form-data">
										<div id="side-sortables" class="accordion-container">
											<ul class="outer-border">
												<li class="control-section accordion-section  open add-page" id="add-page">
													<h3 class="accordion-section-title hndle" tabindex="0"> {{ translate('Custom Link') }} <span class="screen-reader-text">{{ translate('Press return or enter to expand') }}</span></h3>
													<div class="accordion-section-content ">
														<div class="inside">
															<div class="customlinkdiv" id="customlinkdiv">
																<p id="menu-item-url-wrap">
																	<label class="howto" for="custom-menu-item-url"> <span>{{ translate('URL') }}</span>&nbsp;&nbsp;&nbsp;
																		<input id="custom-menu-item-url" name="url" type="text" class="menu-item-textbox " placeholder="url">
																	</label>
																</p>
																<p id="menu-item-name-wrap" class="mt-2">
																	<label class="howto" for="custom-menu-item-name"> <span>{{ translate('Label') }}</span>&nbsp;
																		<input id="custom-menu-item-name" name="label" type="text" class="regular-text menu-item-textbox input-with-default-title" title="Label menu">
																	</label>
																</p>
																<p class="button-controls">
																	<a href="javascript:;" onclick="addcustommenu()"  class="button-secondary submit-add-to-menu right"  >{{ translate('Add menu item') }}</a>
																	<span class="spinner" id="spincustomu"></span>
																</p>
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</form>


									{{-- PAGE LINK --}}
									<div class="clear"></div>

									<form action="{{ route('dashboard.menu-builder.page.store') }}" class="nav-menu-meta mt-3" method="post" enctype="multipart/form-data">
										@csrf
										<input type="hidden" name="idmenu" value="@if(isset($indmenu)){{$indmenu->id}}@endif" />
										<div id="side-sortables" class="accordion-container">
											<ul class="outer-border">
												<li class="control-section accordion-section  open add-page" id="add-page">
													<h3 class="accordion-section-title hndle" tabindex="0"> {{ translate('Pages') }} <span class="screen-reader-text">{{ translate('Press return or enter to expand') }}</span></h3>
													<div class="accordion-section-content ">
														<div class="inside">
															<div class="customlinkdiv" id="customlinkdiv">
																
																@forelse (pages() as $page)
																
																<div class="form-check">
																	<input class="form-check-input mt-3px" name="page_id[]" type="checkbox" value="{{ $page->id }}" id="defaultCheck{{ $page->id }}">
																	<label class="form-check-label ml-4" for="defaultCheck{{ $page->id }}" title="{{ $page->page_name }}">
																		{{ Str::limit($page->page_name,30) }}
																	</label>
																</div>

																@empty
																	
																@endforelse



																<p class="button-controls mt-3">
																	<button type="submit"class="button-secondary right">{{ translate('Add menu item') }}</button>
																	<span class="spinner" id="spincustomu"></span>
																</p>
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</form>

								</div>

								@endif
								<div id="menu-management-liquid">
									<div id="menu-management">
										<form id="update-nav-menu" action="" method="post" enctype="multipart/form-data">
											<div class="menu-edit ">
												<div id="nav-menu-header">
													<div class="major-publishing-actions">
														<label class="menu-name-label howto open-label" for="menu-name"> <span>{{ translate('Name') }}</span>
															<input name="menu-name" 
																	id="menu-name" 
																	type="text" 
																	class="menu-name regular-text menu-item-textbox" 
																	title="Enter menu name" 
																	value="@if(isset($indmenu)){{ $indmenu->name }}@endif">
															<input type="hidden" id="idmenu" value="@if(isset($indmenu)){{$indmenu->id}}@endif" />
														</label>

														@if(request()->has('action'))
														<div class="publishing-action">
															<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">{{ translate('Create menu') }}</a>
														</div>
														@elseif(request()->has("menu"))
														<div class="publishing-action">
															<a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">{{ translate('Save menu') }}</a>
															<span class="spinner" id="spincustomu2"></span>
														</div>

														@else
														<div class="publishing-action">
															<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">{{ translate('Create menu') }}</a>
														</div>
														@endif
													</div>
												</div>
												<div id="post-body">
													<div id="post-body-content">

														@if(request()->has("menu"))
														<h3>{{ translate('Menu Structure') }}</h3>
														<div class="drag-instructions post-body-plain" style="">
															<p>
																{{ translate('Place each item in the order you prefer. Click on the arrow to the right of the item to display more configuration options.') }}
															</p>
														</div>

														@else
														<h3>{{ translate('Menu Creation') }}</h3>
														<div class="drag-instructions post-body-plain" style="">
															<p>
																{{ translate('Please enter the name and select "Create menu" button') }}
															</p>
														</div>
														@endif

														<ul class="menu ui-sortable" id="menu-to-edit">
															@if(isset($menus))
															@foreach($menus as $m)
															<li id="menu-item-{{$m->id}}" class="menu-item menu-item-depth-{{$m->depth}} menu-item-page menu-item-edit-inactive" style="display: list-item;">
																<dl class="menu-item-bar">
																	<dt class="menu-item-handle">
																		<span class="item-title"> <span class="menu-item-title"> <span id="menutitletemp_{{$m->id}}">{{ $m->label }}</span> <span style="color: transparent;">|{{$m->id}}|</span> </span> <span class="is-submenu" style="@if($m->depth==0)display: none;@endif">{{ translate('Subelement') }}</span> </span>
																		<span class="item-controls"> <span class="item-type">{{ translate('Link') }}</span> <span class="item-order hide-if-js"> <a href="{{ $currentUrl }}?action=move-up-menu-item&menu-item={{$m->id}}&_wpnonce=8b3eb7ac44" class="item-move-up"><abbr title="Move Up">↑</abbr></a> | <a href="{{ $currentUrl }}?action=move-down-menu-item&menu-item={{$m->id}}&_wpnonce=8b3eb7ac44" class="item-move-down"><abbr title="Move Down">↓</abbr></a> </span> <a class="item-edit" id="edit-{{$m->id}}" title=" " href="{{ $currentUrl }}?edit-menu-item={{$m->id}}#menu-item-settings-{{$m->id}}"> </a> </span>
																	</dt>
																</dl>

																<div class="menu-item-settings" id="menu-item-settings-{{$m->id}}">
																	<input type="hidden" class="edit-menu-item-id" name="menuid_{{$m->id}}" value="{{$m->id}}" />
																	<p class="description description-thin">
																		<label for="edit-menu-item-title-{{$m->id}}"> {{ translate('Label') }}
																			<br>
																			<input type="text" id="idlabelmenu_{{$m->id}}" class="widefat edit-menu-item-title" placeholder="Label" name="idlabelmenu_{{$m->id}}" value="{{ $m->label}}">
																		</label>
																	</p>

																	<p class="field-css-classes description description-thin mt-10">
																		<label for="edit-menu-item-classes-{{$m->id}}"> {{ translate('Class CSS (optional)') }}
																			<br>
																			<input type="text" id="clases_menu_{{$m->id}}" class="widefat code edit-menu-item-classes" name="clases_menu_{{$m->id}}" value="{{$m->class}}">
																		</label>
																	</p>

																	<p class="field-css-url description description-wide mt-10">
																		<label for="edit-menu-item-url-{{$m->id}}"> {{ translate('URL') }}
																			<br>
																			<input type="text" id="url_menu_{{$m->id}}" class="widefat code edit-menu-item-url" id="url_menu_{{$m->id}}" value="{{$m->link}}">
																		</label>
																	</p>

																	@if(!empty($roles))
																	<p class="field-css-role description description-wide">
																		<label for="edit-menu-item-role-{{$m->id}}"> {{ translate('Role') }}
																			<br>
																			<select id="role_menu_{{$m->id}}" class="widefat code edit-menu-item-role" name="role_menu_[{{$m->id}}]" >
																				<option value="0">{{ translate('Select Role') }}</option>
																				@foreach($roles as $role)
																					<option @if($role->id == $m->role_id) selected @endif value="{{ $role->$role_pk }}">{{ ucwords($role->$role_title_field) }}</option>
																				@endforeach
																			</select>
																		</label>
																	</p>
																	@endif

																	<div class="menu-item-actions description-wide submitbox">

																		<a class="item-delete submitdelete deletion btn-sm btn-danger text-white" id="delete-{{$m->id}}" href="{{ $currentUrl }}?action=delete-menu-item&menu-item={{$m->id}}&_wpnonce=2844002501">{{ translate('Delete') }}</a>
																		<a class="item-cancel submitcancel hide-if-no-js btn-sm btn-secondary text-white ml-2 mr-2" id="cancel-{{$m->id}}" href="{{ $currentUrl }}?edit-menu-item={{$m->id}}&cancel=1424297719#menu-item-settings-{{$m->id}}">{{ translate('Cancel') }}</a>
																		<a onclick="getmenus()" class="updatemenu btn-sm btn-secondary text-white" id="update-{{$m->id}}" href="javascript:void(0)">{{ translate('Update') }}</a>

																	</div>

																</div>
																<ul class="menu-item-transport"></ul>
															</li>
															@endforeach
															@endif
														</ul>
														<div class="menu-settings">

														</div>
													</div>
												</div>
												<div id="nav-menu-footer">
													<div class="major-publishing-actions">

														@if(request()->has('action'))
														<div class="publishing-action">
															<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">{{ translate('Create menu') }}</a>
														</div>
														@elseif(request()->has("menu"))
														<span class="delete-action"> <a class="submitdelete deletion menu-delete" onclick="deletemenu()" href="javascript:void(9)">{{ translate('Delete menu') }}</a> </span>
														<div class="publishing-action">

															<a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">{{ translate('Save menu') }}</a>
															<span class="spinner" id="spincustomu2"></span>
														</div>

														@else
														<div class="publishing-action">
															<a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">{{ translate('Create menu') }}</a>
														</div>
														@endif
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<div class="clear"></div>
					</div>

					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="clear"></div>
		</div>
	</div>
</div>
