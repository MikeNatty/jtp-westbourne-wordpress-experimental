type RadioButtonProps = {
  onChange: (value: string) => void;
  value: string;
  option: {
    value: string;
    label: string;
  };
  name: string;
};

export function RadioButton({
  value,
  option,
  onChange,
  name,
}: RadioButtonProps) {
  const checked = value === option.value;

  return (
    <label
      htmlFor={`radio-${option.value}`}
      className="button"
      // data-size="small"
      data-button-theme={checked ? 'primary' : 'tertiary'}
      style={{ textAlign:'left', borderRadius:'100px', lineHeight:1.2 }}
    >
      <input
        type="radio"
        id={`radio-${option.value}`}
        className="is-hidden"
        name={name}
        value={option.value}
        onChange={(e) => onChange(e.target.value)}
        checked={checked}
        style={{ aspectRatio:1 }}
        hidden
      />
      {option.label}
    </label>
  );
}
