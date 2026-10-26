    </main>
    <script>
      // Demande confirmation avant une suppression (boutons avec data-confirmer="message")
      document.querySelectorAll('[data-confirmer]').forEach((bouton) => {
        bouton.addEventListener('click', (e) => {
          if (!confirm(bouton.dataset.confirmer)) e.preventDefault();
        });
      });
    </script>
  </body>
</html>
