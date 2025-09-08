<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Button } from '@/components/ui/button'

const canClockIn = ref(false)

const fetchLastTimeEntry = async () => {
  try {
    const response = await fetch('/api/time/entry/last')
    if (response.ok) {
      const result = await response.json()
      // Show "Clock In" button only if the last entry has both clockIn and clockOut
      canClockIn.value = result.hasClockOut
    } else {
      console.error('Failed to fetch last time entry:', response.statusText)
    }
  } catch (error) {
    console.error('Error fetching last time entry:', error)
  }
}

const clockIn = async () => {
  try {
    const response = await fetch('/api/clock-in', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    })
    const result = await response.json()
    if (result.success) {
      alert('Clocked In!')
      await fetchLastTimeEntry() // Refresh the button state
    } else {
      alert(`Error: ${result.error}`)
    }
  } catch (error) {
    console.error('Clock In Failed:', error)
  }
}

const clockOut = async () => {
  try {
    const response = await fetch('/api/clock-out', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    })
    const result = await response.json()
    if (result.success) {
      alert('Clocked Out!')
      await fetchLastTimeEntry() // Refresh the button state
    } else {
      alert(`Error: ${result.error}`)
    }
  } catch (error) {
    console.error('Clock Out Failed:', error)
  }
}

const logout = async () => {
  try {
    const response = await fetch('/logout', { method: 'POST' })
    if (response.ok) {
      window.location.href = '/login'
    } else {
      alert('Logout Failed')
    }
  } catch (error) {
    console.error('Logout Failed:', error)
  }
}

onMounted(() => {
  fetchLastTimeEntry() // Fetch the current state on component load
})
</script>

<template>
  <div id="time-clock">
    <div class="w-full max-w-md mx-auto p-6 bg-white rounded-lg shadow-lg border">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Time Clock</h1>
        <p class="text-gray-600">Track your work hours</p>
      </div>
      
      <div class="space-y-4">
        <div class="flex gap-4">
          <Button 
            v-if="canClockIn" 
            @click="clockIn" 
            variant="default"
            class=""
          >
            Clock In
          </Button>
          <Button 
            v-if="!canClockIn" 
            @click="clockOut" 
            class=""
          >
            Clock Out
          </Button>
        </div>
        <Button 
          @click="logout" 
          variant="destructive"
          class=""
        >
          Logout
        </Button>
      </div>
    </div>

    <div class="mt-8 text-center">
    </div>
  </div>
</template>

<style scoped>
#time-clock {
  margin-top: 20px;
}
</style>
