type RadioButtonProps = {
  onChange: (params: { value: string; checked: boolean }) => void;
  option: {
    value: string;
    label: string;
  };
  name: string;
};

export function CheckboxButton({ option, onChange, name }: RadioButtonProps) {
  return (
    <>
      <label
        htmlFor={`checkbox-${option.value}`}
        className="button"
        // data-size="small"
        data-button-theme="tertiary"
        style={{ textAlign:'left', borderRadius:'100px', lineHeight:1.2 }}
      >
        <input
          type="checkbox"
          id={`checkbox-${option.value}`}
          name={name}
          value={option.value}
          onChange={(e) =>
            onChange({
              value: option.value,
              checked: e.target.checked,
            })
          }
          data-checkbox-theme="rounded"
          style={{aspectRatio:1}}
        ></input>
        {option.label}
      </label>
    </>
  );
}
