<script setup lang="ts">
import { ref } from 'vue'
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table/index.js'

// Define the invoice type
interface Invoice {
  invoice: string
  paymentStatus: string
  paymentMethod: string
  totalAmount: string
}


// Declare invoices as a reactive reference
const invoices = ref<Invoice[]>([
  {
    invoice: "INV001",
    paymentStatus: "Paid",
    paymentMethod: "Credit Card",
    totalAmount: "$250.00"
  },
  {
    invoice: "INV002", 
    paymentStatus: "Pending",
    paymentMethod: "PayPal",
    totalAmount: "$150.00"
  },
  {
    invoice: "INV003",
    paymentStatus: "Unpaid", 
    paymentMethod: "Bank Transfer",
    totalAmount: "$350.00"
  }
])
</script>

<template>
  <div class="p-4 border rounded">
    <h3 class="text-lg font-semibold mb-4">Time Table</h3>
    
    <!-- Debug: Test if components are working -->
    <div class="mb-4 p-2 bg-yellow-100">
      <p>Debug: Table component test</p>
      <p>Invoices count: {{ invoices.length }}</p>
    </div>
    
    <Table>
      <TableCaption>A list of your recent invoices.</TableCaption>
      <TableHeader>
        <TableRow>
          <TableHead class="w-[100px]">
            Invoice
          </TableHead>
          <TableHead>Status</TableHead>
          <TableHead>Method</TableHead>
          <TableHead class="text-right">
            Amount
          </TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="invoice in invoices" :key="invoice.invoice">
          <TableCell class="font-medium">
            {{ invoice.invoice }}
          </TableCell>
          <TableCell>{{ invoice.paymentStatus }}</TableCell>
          <TableCell>{{ invoice.paymentMethod }}</TableCell>
          <TableCell class="text-right">
            {{ invoice.totalAmount }}
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
  </div>
</template>
