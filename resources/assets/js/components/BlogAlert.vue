<template lang="html">
    <div :class="['alert', 'alert-' + type]" role="alert">
        {{ message }}
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