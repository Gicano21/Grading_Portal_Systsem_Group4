<?php foreach ($config['fields'] as $name => $field): ?>
    <label for="<?= esc($name) ?>"><?= esc($field['label']) ?></label>
    <?php if ($field['type'] === 'select'): ?>
        <select id="<?= esc($name) ?>" name="<?= esc($name) ?>" required>
            <?php foreach ($field['options'] as $option): ?>
                <option value="<?= esc($option) ?>" <?= (string) old($name, $record[$name] ?? '') === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <input
            id="<?= esc($name) ?>"
            name="<?= esc($name) ?>"
            type="<?= esc($field['type']) ?>"
            value="<?= esc((string) old($name, $record[$name] ?? '')) ?>"
            <?= $field['type'] === 'number' ? 'min="1" max="120"' : '' ?>
            <?= empty($field['optional']) ? 'required' : '' ?>
        >
    <?php endif; ?>
<?php endforeach; ?>
