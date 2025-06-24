<h2>🧾 Liste des tickets à assigner</h2>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Titre</th>
      <th>Description</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets as $ticket): ?>
      <tr>
        <td><?= $ticket['id'] ?></td>
        <td><?= htmlspecialchars($ticket['subject']) ?></td>
        <td><?= htmlspecialchars($ticket['message']) ?></td>
        <td>
          <button onclick="openModal(<?= $ticket['id'] ?>)">Assigner</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal -->
<div id="assignModal" style="display:none; position:fixed; top:20%; left:30%; background:white; padding:20px; border:1px solid #ccc;">
  <form method="POST" action="/ticket/assign">
    <input type="hidden" name="ticket_id" id="modal_ticket_id">
    <label for="agent_id">Choisir un agent :</label>
    <select name="agent_id" required>
      <option value="">-- Sélectionner --</option>
      <?php foreach ($agents as $agent): ?>
        <option value="<?= $agent['id'] ?>"><?= htmlspecialchars($agent['firstname'] . ' ' . $agent['lastname']) ?></option>
      <?php endforeach; ?>
    </select>
    <br><br>
    <button type="submit">Assigner</button>
    <button type="button" onclick="closeModal()">Annuler</button>
  </form>
</div>

<script>
function openModal(ticketId) {
  document.getElementById('modal_ticket_id').value = ticketId;
  document.getElementById('assignModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('assignModal').style.display = 'none';
}
</script>
