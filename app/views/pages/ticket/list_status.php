<h2>Liste des tickets</h2>
<table border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Sujet</th>
      <th>Status</th>
      <th>Changer le statut</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets as $ticket): ?>
      <tr>
        <td><?= $ticket['id'] ?></td>
        <td><?= htmlspecialchars($ticket['subject']) ?></td>
        <td><?= \app\constants\TicketStatus::label((int)$ticket['status']) ?></td>
        <td>
          <form method="POST" action="/ticket/status/update">
            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
            <select name="status">
              <?php foreach ($statuses as $code => $label): ?>
                <option value="<?= $code ?>" <?= $ticket['status'] == $code ? 'selected' : '' ?>>
                  <?= $label ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="submit">Changer</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
