<?php
/* Copyright (c) Anuko International Ltd. https://www.anuko.com
License: See license.txt */

require_once('initialize.php');
import('ttDate');
import('ttInvoiceHelper');
import('ttClientHelper');
import('form.Form');

// Access checks.
if (!(ttAccessAllowed('manage_invoices') || ttAccessAllowed('view_client_invoices'))) {
  header('Location: access_denied.php');
  exit();
}
if (!$user->isPluginEnabled('iv')) {
  header('Location: feature_disabled.php');
  exit();
}

$cl_client_id = (int)$request->getParameter('id');
$client = ttClientHelper::getClient($cl_client_id, true);
if (!$client) {
  header('Location: access_denied.php');
  exit();
}

// Fetch all invoices for the selected client.
$invoices = ttInvoiceHelper::getInvoicesForClient($cl_client_id);

// Variables to summarize account information.
$subtotal = 0;
$tax = 0;
$total = 0;
$invoice_items_summary = [];

foreach ($invoices as $invoice) {
  $invoice_items = ttInvoiceHelper::getInvoiceItems($invoice['id']);
  $invoice_subtotal = 0;
  $invoice_tax = 0;

  // Calculate subtotal and tax for each invoice.
  foreach ($invoice_items as $item) {
    $invoice_subtotal += $item['cost'];
    if ($invoice['taxrate'] > 0 && $item['type'] != 2) {
      $invoice_tax += $item['cost'] * $invoice['taxrate'];
    }
  }

  $invoice_total = $invoice_subtotal + $invoice_tax;

  $subtotal += $invoice_subtotal;
  $tax += $invoice_tax;
  $total += $invoice_total;

  // Add to summary for display.
  $invoice_items_summary[] = [
    'invoice_id' => $invoice['id'],
    'invoice_date' => $invoice['date'],
    'invoice_name' => $invoice['name'],
    'subtotal' => $invoice_subtotal,
    'tax' => $invoice_tax,
    'total' => $invoice_total,
  ];
}

$currency = $user->getCurrency();
$decimalMark = $user->getDecimalMark();

$smarty->assign('subtotal', $currency . ' ' . str_replace('.', $decimalMark, sprintf('%8.2f', round($subtotal, 2))));
$smarty->assign('tax_total', $currency . ' ' . str_replace('.', $decimalMark, sprintf('%8.2f', round($tax_total, 2))));
$smarty->assign('total_due', $currency . ' ' . str_replace('.', $decimalMark, sprintf('%8.2f', round($total_due, 2))));

foreach ($invoice_items_summary as &$item) {
  $item['subtotal'] = $currency . ' ' . str_replace('.', $decimalMark, sprintf('%8.2f', round($item['subtotal'], 2)));
  $item['tax'] = $currency . ' ' . str_replace('.', $decimalMark, sprintf('%8.2f', round($item['tax'], 2)));
  $item['total'] = $currency . ' ' . str_replace('.', $decimalMark, sprintf('%8.2f', round($item['total'], 2)));
}

$smarty->assign('client_name', $client['name']);
$smarty->assign('client_address', $client['address']);
$smarty->assign('billing_email', $client['billing_email']);
$smarty->assign('invoice_items_summary', $invoice_items_summary);
$smarty->assign('title', 'Statement of Accounts');
$smarty->assign('content_page_name', 'new_invoice.tpl');
$smarty->display('new_invoice.tpl');