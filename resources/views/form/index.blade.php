@extends('layouts.master')
@section('content')

      <div class="container">
         <div class="form-preview-section mt-5 hide">
            <div class="preview-buttons-wrapper">
               <button type="button" class="btn btn-outline-primary custom-btn-outline close-preview-btn max-content">Close
               Preview</button>
            </div>
            <div class="form-preview mt-4"><span class="preview-empty-state">Your form is empty, please add some elements.</span></div>
         </div>
         <div class="form-builder">
            <div class="row mt-5">
               <hr>
            </div>
            <div class="row g-2">
               <div class="col-md-9 element-drop-zone ui-droppable">
                  <div class="form-builder-area ui-sortable" id="sortable-area">
                     <div class="form-empty-state" style="">
                        <span>Place form elements in this field to ask the applicant any questions, related to the application for your open call</span>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-builder-items hide">
                     <div class="header">
                        <strong>Form element</strong>
                        <button class="close-btn close-builder-section"></button>
                     </div>
                     <div class="body mt-3">
                        <div class="form-element-item draggable ui-draggable ui-draggable-handle" data-type="text">
                           <img src="{{ asset('public/images/text-input.png') }}" alt="Text Input" width="24px" height="24px">
                           <span>Short Text</span>
                        </div>
                        <div class="form-element-item draggable ui-draggable ui-draggable-handle" data-type="radio">
                           <img src="{{ asset('public/images/radio-btn.png') }}" alt="Radio Button" width="24px" height="24px">
                           <span>Radio button</span>
                        </div>
                     </div>
                  </div>
                  <div class="form-builder-menu">
                     <button type="button" class="btn btn-primary custom-btn-filled add-form-elements">Add Form
                     Element</button>
                     <button type="button" class="btn btn-outline-primary custom-btn-outline mt-2 show-preview-btn">Preview</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="hide">
         <div class="form-element-section mb-5 draggable-section" id="radio-field" data-type="radio">
            <div class="header">
               <div class="left-icon">
                  <img src="{{ asset('public/images/radio-btn.png') }}" alt="Radio Input" width="24px" height="24px">
                  <strong>Radio Button</strong>
               </div>
               <div class="right-icons">
                  <span class="move-item-btn"></span>
                  <button class="duplicate-item-btn"></button>
                  <button class="close-item-btn"></button>
               </div>
            </div>
            <div class="section-body">
               <div class="field-heading mt-3" contenteditable="true" id="field-heading" name="field-heading">Type your question here</div>
               <div class="field-description mt-2" contenteditable="true" id="field-description" name="field-description">Type a description here (optional)</div>
               <div class="mt-3">
                  <ul class="list-group ui-sortable">
                     <li class="list-group-item">
                        <div class="item-content radio-options">
                           <input type="radio" name="options" class="option-radio">
                           <span name="radio_option" contenteditable="true">Type your option 1</span>
                        </div>
                        <div class="item-buttons">
                           <span class="move-list-item-btn ui-sortable-handle">&nbsp;&nbsp;&nbsp;</span>
                           <button class="delete-item-btn"></button>
                        </div>
                     </li>
                     <li class="list-group-item">
                        <div class="item-content radio-options">
                           <input type="radio" name="options" class="option-radio">
                           <span name="radio_option" contenteditable="true">Type your option 2</span>
                        </div>
                        <div class="item-buttons">
                           <span class="move-list-item-btn ui-sortable-handle">&nbsp;&nbsp;&nbsp;</span>
                           <button class="delete-item-btn"></button>
                        </div>
                     </li>
                     <li class="list-group-item">
                        <div class="item-content radio-options">
                           <input type="radio" name="options" class="option-radio">
                           <span name="radio_option" contenteditable="true">Type your option 3</span>
                        </div>
                        <div class="item-buttons">
                           <span class="move-list-item-btn ui-sortable-handle">&nbsp;&nbsp;&nbsp;</span>
                           <button class="delete-item-btn"></button>
                        </div>
                     </li>
                     <li class="list-group-item add-option-btn"><span><i class="fa fa-plus" aria-hidden="true"></i>
                        &nbsp;Add other option</span>
                     </li>
                  </ul>
               </div>
               <h6 class="mt-3">Edit Settings</h6>
               <div class="form-check form-switch mt-3">
                  <input class="form-check-input" type="checkbox" role="switch" id="field-required-switch" name="field-required-switch">
                  <label class="form-check-label" for="field-required-switch">Required</label>
               </div>
               <div class="form-check form-switch mt-3">
                  <input class="form-check-input" type="checkbox" role="switch" id="field-sort-options" name="field-sort-options">
                  <label class="form-check-label" for="field-sort-options">Sort options in
                  alphabetical
                  order</label>
               </div>
            </div>
         </div>
         <div class="form-element-section mb-5 draggable-section" id="text-field" data-type="text">
            <div class="header">
               <div class="left-icon">
                  <img src="{{ asset('public/images/text-input.png') }}" alt="Text Input" width="24px" height="24px">
                  <strong>Short Text</strong>
               </div>
               <div class="right-icons">
                  <span class="move-item-btn"></span>
                  <button class="duplicate-item-btn"></button>
                  <button class="close-item-btn"></button>
               </div>
            </div>
            <div class="section-body">
               <div class="field-heading mt-3" contenteditable="true" id="field-heading" name="field-heading">Type your question here</div>
               <div class="field-description mt-2" contenteditable="true" id="field-description" name="field-description">Type a description here (optional)</div>
               <div class="mt-3">
                  <input class="form-control form-control-sm mt-3 field-input" type="text" id="dummy-text-field" name="dummy-text-field">
               </div>
               <h6 class="mt-3">Edit Settings</h6>
               <div class="form-check form-switch mt-3">
                  <input class="form-check-input" type="checkbox" role="switch" id="field-required-switch" name="field-required-switch">
                  <label class="form-check-label" for="field-required-switch">Required</label>
               </div>
               <div class="form-check form-switch mt-3">
                  <input class="form-check-input" type="checkbox" role="switch" id="field-max-characters" name="field-max-characters" checked="">
                  <label class="form-check-label" for="field-max-characters">Max characters</label>
               </div>
               <input class="form-control form-control-sm mt-3 field-input width-30" type="text" placeholder="50" value="50" id="field-max-characters-value" name="field-max-characters-value">
            </div>
         </div>
      </div>

      



@endsection