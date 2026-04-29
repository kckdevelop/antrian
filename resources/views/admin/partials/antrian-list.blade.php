<script>
setInterval(() => {
  fetch("{{ route('dashboard.index') }}")
    .then(res => res.json())
    .then(data => {
      document.getElementById('antrian-container').innerHTML = data.antrian;
    });
}, 10000); // Refresh tiap 10 detik
</script>