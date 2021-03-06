USE [KAO_wssp]
GO
/****** Object:  Table [dbo].[checkActBasicasSup_Banco]    Script Date: 8/4/2019 16:08:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[checkActBasicasSup_Banco](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[Codigo] [nchar](10) NULL,
	[Titulo] [nchar](100) NULL,
	[Descripcion] [nchar](255) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[checkActBasicasSup_CAB]    Script Date: 8/4/2019 16:08:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[checkActBasicasSup_CAB](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[codChecklist] [nchar](10) NULL,
	[fechaCreacion] [date] NULL,
	[evaluador] [nchar](13) NULL,
	[supervisor] [nchar](13) NULL,
	[semana] [nchar](25) NULL,
	[empresa] [nchar](10) NULL,
	[bodega] [nchar](5) NULL,
	[estado] [int] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[checkActBasicasSup_MOV]    Script Date: 8/4/2019 16:08:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[checkActBasicasSup_MOV](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[codCAB] [nchar](10) NULL,
	[codCheckItem] [nchar](10) NULL,
	[checked] [bit] NULL,
	[comentario] [nchar](200) NULL
) ON [PRIMARY]
GO
/****** Object:  StoredProcedure [dbo].[sp_genera_codCheckActBasicasSup_CAB]    Script Date: 8/4/2019 16:08:50 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_genera_codCheckActBasicasSup_CAB]
@tipo_doc nchar(3)
AS
BEGIN
    DECLARE @contador AS INT
		SET @contador = (SELECT COUNT(*)+1 FROM dbo.checkActBasicasSup_CAB)
		
        IF (@contador>=1) BEGIN
			IF (@contador<10) BEGIN
				SELECT @tipo_doc +'0000'+ CONVERT (Varchar ,@contador)
			END
			ELSE IF (@contador<100) BEGIN    
				SELECT @tipo_doc +'000'+ CONVERT (Varchar ,@contador)
			END
			ELSE IF (@contador<1000) BEGIN
				SELECT @tipo_doc +'00'+ CONVERT (Varchar ,@contador)
			END
			ELSE IF (@contador<1000) BEGIN
				SELECT @tipo_doc +'0'+ CONVERT (Varchar ,@contador)
			END
			
		END	
END	
GO
