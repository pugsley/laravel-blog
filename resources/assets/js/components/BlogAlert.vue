<template lang="html">
    <div>
        <div :class="['alert floating-alert', 'alert-' + type]" role="alert">
            {{ message }}
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'message',
            'type'
        ]
    }
</script>