<?php


class SweetAlert
{
    private string $type;
    private string $title;
    private string $text;

    const SUCCESS = 0;
    const ERROR = 1;
    const INFO = 2;
    const WARNING = 3;
    /**
     * SweetAlert constructor.
     * @param string $type
     * @param string $title
     * @param string $text
     */
    public function __construct(string $type, string $title, string $text)
    {
        switch ($type){
            case 1:
                $this->type = 'error';
                break;
            case 2:
                $this->type = 'info';
                break;
            case 3:
                $this->type = 'warning';
                break;
            default:
                $this->type = 'success';
                break;
        }
        $this->title = $title;
        $this->text = $text;

        $this->showAlert();
    }

    public function showAlert():void{
        ?>
        <script type="text/javascript">
            Swal.fire(
                "<?= translate($this->getTitle());?>",
                "<?= translate($this->getText());?>",
                "<?= translate($this->getType());?>"
            )
        </script>
        <?php
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }


}