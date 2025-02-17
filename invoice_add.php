<?php
/* Copyright (c) Anuko International Ltd. https://www.anuko.com
License: See license.txt */

require_once('initialize.php');
import('form.Form');
import('ttTeamHelper');
import('ttGroupHelper');
import('ttInvoiceHelper');

// Access checks.
if (!ttAccessAllowed('manage_invoices')) {
  header('Location: access_denied.php');
  exit();
}
if (!$user->isPluginEnabled('iv')) {
  header('Location: feature_disabled.php');
  exit();
}
// End of access checks.

$cl_date = $cl_client = $cl_project = $cl_number = $cl_start = $cl_finish = null;
if ($request->isPost()) {
  $cl_date = $request->getParameter('date');
  $duedateselect = (int)$request->getParameter('client');
  $cl_project = $request->getParameter('project');
  $cl_number = trim($request->getParameter('number'));
  $cl_start = $request->getParameter('start');
  $cl_finish = $request->getParameter('finish');
}

$form = new Form('invoiceForm');
$form->addInput(array('type'=>'datefield','name'=>'date','size'=>'20','value'=>$cl_date));

function debug_to_console($data) {
  $file = 'debug.log';
  $timestampp = date('Y-m-d H:i:s');
  
  $output = $data;
  if (is_array($output)){
      $output = implode(',', $output);
  }
  $formattedMessage = "[{". $timestampp ."}] {".$output."}\n";

  file_put_contents($file, $formattedMessage, FILE_APPEND);

}

// Dropdown for projects.
$show_project = MODE_PROJECTS == $user->getTrackingMode() || MODE_PROJECTS_AND_TASKS == $user->getTrackingMode();
if ($show_project) {
  $projects = ttGroupHelper::getActiveProjects();
  $form->addInput(array('type'=>'combobox', 'onchange="updatePhp()"','name'=>'project','data'=>$projects,'datakeys'=>array('id','name'),'value'=>$cl_project,'empty'=>array(''=>$i18n->get('dropdown.all'))));
}

$duedatetype = array(
  0 => array(
      'id' => 1,
      'name' => '20th Next Month'
  ),
  1 => array(
      'id' => 2,
      'name' => '7 days from today'
  ),
  2 => array(
    'id' => 3,
    'name' => 'Today'
)
);


// Dropdown for clients if the clients plugin is enabled.

 // debug_to_console($duedatetype);
  $form->addInput(array('type'=>'combobox','name'=>'client','data'=>$duedatetype,'datakeys'=>array('id','name'),'value'=>$duedateselect,'empty'=>array(''=>$i18n->get('dropdown.select'))));

$form->addInput(array('type'=>'text','maxlength'=>'100','name'=>'number','value'=>$cl_number));
$form->addInput(array('type'=>'datefield','maxlength'=>'20','name'=>'start','value'=>$cl_start));
$form->addInput(array('type'=>'datefield','maxlength'=>'20','name'=>'finish','value'=>$cl_finish));
$form->addInput(array('type'=>'submit','name'=>'btn_submit','value'=>$i18n->get('button.add')));

if ($request->isPost()) {
  // Validate user input.
  if (!ttValidString($cl_number)) $err->add($i18n->get('error.field'), $i18n->get('form.invoice.number'));
  if (!ttValidDate($cl_date)) $err->add($i18n->get('error.field'), $i18n->get('label.date'));
  if (!$duedateselect) $err->add("no due date selected");
  if (!ttValidDate($cl_start)) $err->add($i18n->get('error.field'), $i18n->get('label.start_date'));
  if (!ttValidDate($cl_finish)) $err->add($i18n->get('error.field'), $i18n->get('label.end_date'));

  $cl_client = ttGroupHelper::getActiveClients(false, $cl_project);


 // $duedateselect = $duedateselect; // 'paymentoption'=>$duedateselect,
  debug_to_console("Riggas");
  debug_to_console($duedateselect);

  $fields = array('paymentoption'=>$duedateselect, 'date'=>$cl_date,'name'=>$cl_number,'client_id'=>$cl_client,'project_id'=>$cl_project,'start_date'=>$cl_start,'end_date'=>$cl_finish);
  if ($err->no()) {
    if (ttInvoiceHelper::getInvoiceByName($cl_number))
      $err->add($i18n->get('error.invoice_exists'));

    if (!ttInvoiceHelper::invoiceableItemsExist($fields))
      $err->add($i18n->get('error.no_invoiceable_items'));
  }

  if ($err->no()) {
    // Now we can go ahead and create our invoice.
   
    if (ttInvoiceHelper::createInvoice($fields)) {
      //header('Location: invoices.php');

      exit();
    } else {
      $err->add($i18n->get('error.db'));
    }
  }
} // isPost

$smarty->assign('forms', array($form->getName()=>$form->toArray()));
$smarty->assign('onload', 'onLoad="document.invoiceForm.number.focus()"');
$smarty->assign('show_project', $show_project);
$smarty->assign('title', $i18n->get('title.add_invoice'));
$smarty->assign('content_page_name', 'invoice_add.tpl');
$smarty->display('index.tpl');
